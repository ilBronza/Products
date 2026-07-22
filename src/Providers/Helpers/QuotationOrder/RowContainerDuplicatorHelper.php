<?php

namespace IlBronza\Products\Providers\Helpers\QuotationOrder;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Providers\Helpers\QuotationOrder\Traits\DuplicatesRowcontainerRelationsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RowContainerDuplicatorHelper
{
	use DuplicatesRowcontainerRelationsTrait;

	public array $formParameters;
	public ProductPackageBaseRowcontainerModel $originalRowContainer;
	public ProductPackageBaseRowcontainerModel $newRowContainer;

	public ?Collection $rows = null;

	protected ?int $duplicateDelayInDays = null;

	protected bool $duplicateDelayInDaysResolved = false;

	/** @var array<string, array<string, string>> */
	protected array $tableTemporalColumnsCache = [];

	protected array $datesDelayExcludedAttributes = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	public function __construct(ProductPackageBaseRowcontainerModel $originalRowContainer, array $formParameters)
	{
		$this->originalRowContainer = $originalRowContainer;
		$this->formParameters = $formParameters;
	}

	public function getOriginalRowContainer() : ProductPackageBaseRowcontainerModel
	{
		return $this->originalRowContainer;
	}

	public function loadRows()
	{
		$this->rows = $this->getOriginalRowContainer()->rows()->with('extraFields')->get();
	}

	public function duplicateRowContainer()
	{
		$container = $this->getOriginalRowContainer();

		$this->newRowContainer = $container->replicate();

		$this->newRowContainer->name = $this->newRowContainer->calculateNewName();
		$this->newRowContainer->frozen = false;

		$this->newRowContainer->save();

		$this->setDatesDelay($this->newRowContainer, $container);

		foreach($container->extraFields->getAttributes() as $attribute => $value)
		{
			if($container->extraFields->getKeyName() == $attribute)
				continue;

			if($container->extraFields()->getForeignKeyName() == $attribute)
				continue;

			$this->newRowContainer->extraFields->$attribute = $value;
		}

		$this->newRowContainer->frozen_parameters = null;

		$this->overrideParameters();

		$this->newRowContainer->save();
	}

	public function overrideParameters() : void
	{
	}

	public function getRows() : Collection
	{
		if ($this->rows === null)
			$this->loadRows();

		return $this->rows;
	}

	public function getFormParameters() : array
	{
		return $this->formParameters;
	}

	public function getFormEventStartsAt() : ?Carbon
	{
		$value = $this->getFormParameters()['event_starts_at'] ?? null;

		if ($value === null || $value === '')
			return null;

		return Carbon::parse($value)->startOfDay();
	}

	/**
	 * Giorni da sommare alle date duplicate.
	 * Null = nessuno shift (data non indicata nel primo form).
	 */
	public function getDuplicateDelayInDays() : ?int
	{
		if ($this->duplicateDelayInDaysResolved)
			return $this->duplicateDelayInDays;

		$this->duplicateDelayInDays = $this->calculateDuplicateDelayInDays();
		$this->duplicateDelayInDaysResolved = true;

		return $this->duplicateDelayInDays;
	}

	protected function calculateDuplicateDelayInDays() : ?int
	{
		$eventStartsAt = $this->getFormEventStartsAt();

		if ($eventStartsAt === null)
			return null;

		$originalStartsAt = $this->getOriginalRowContainer()->getStartsAt();

		if ($originalStartsAt === null)
			return null;

		return (int) Carbon::parse($originalStartsAt)->startOfDay()->diffInDays($eventStartsAt, false);
	}

	public function hasDuplicateDelay() : bool
	{
		return $this->getDuplicateDelayInDays() !== null;
	}

	public function setDatesDelay(Model $model, Model $originalModel) : void
	{
		$delayInDays = $this->getDuplicateDelayInDays();

		if ($delayInDays === null || $delayInDays === 0)
			return;

		foreach ($model->getCasts() as $attribute => $cast)
		{
			if (in_array($attribute, $this->datesDelayExcludedAttributes, true))
				continue;

			if (! $this->isDateCast($cast))
				continue;

			if (! $value = $originalModel->$attribute)
				continue;

			$model->$attribute = Carbon::parse($value)->addDays($delayInDays);

			// echo $attribute . ": " . $model->$attribute . "<br />";

		}

		// die();
	}

	protected function isDateCast(mixed $cast) : bool
	{
		return !! stripos($cast, 'date');
	}

	public function getRawInsertExcludedAttributes() : array
	{
		return [
			'id',
			'created_at',
			'updated_at',
			'deleted_at',
		];
	}

	protected function buildRawInsertPayload(
		Model $original,
		array $overrides = [],
		?ProductPackageBaseRowModel $row = null,
		?string $relationKey = null,
	) : array
	{
		$excludedAttributes = $this->getRawInsertExcludedAttributes();

		if ($row !== null && $relationKey !== null)
			$excludedAttributes = array_merge(
				$excludedAttributes,
				$this->resolveDuplicatePayloadExcludedAttributes($row, $original, $relationKey)
			);

		$payload = array_merge(
			collect($original->getRawOriginal())->except($excludedAttributes)->all(),
			$overrides
		);

		$this->applyDatesDelayToRawPayload($payload, $original->getTable());

		$now = now();

		$payload['created_at'] = $now;
		$payload['updated_at'] = $now;

		return $payload;
	}

	protected function applyDatesDelayToRawPayload(array &$payload, string $table) : void
	{
		$delayInDays = $this->getDuplicateDelayInDays();

		if ($delayInDays === null || $delayInDays === 0)
			return;

		foreach ($this->getTableTemporalColumns($table) as $column => $columnType)
		{
			if (in_array($column, $this->datesDelayExcludedAttributes, true))
				continue;

			if (empty($payload[$column]))
				continue;

			$payload[$column] = $this->formatShiftedTemporalValue(
				$columnType,
				Carbon::parse($payload[$column])->addDays($delayInDays)
			);
		}
	}

	/**
	 * @return array<string, string> column name => schema type (date, timestamp, …)
	 */
	protected function getTableTemporalColumns(string $table) : array
	{
		if (array_key_exists($table, $this->tableTemporalColumnsCache))
			return $this->tableTemporalColumnsCache[$table];

		$columns = [];

		foreach (Schema::getColumns($table) as $column)
		{
			$name = $column['name'];
			$type = strtolower($column['type_name'] ?? '');

			if ($this->isTemporalColumnType($type))
				$columns[$name] = $type;
		}

		return $this->tableTemporalColumnsCache[$table] = $columns;
	}

	protected function isTemporalColumnType(string $type) : bool
	{
		if (in_array($type, ['date', 'datetime', 'timestamp', 'timestamptz'], true))
			return true;

		return str_contains($type, 'timestamp');
	}

	protected function formatShiftedTemporalValue(string $columnType, Carbon $value) : string
	{
		if ($columnType === 'date')
			return $value->format('Y-m-d');

		return $value->format('Y-m-d H:i:s');
	}

	protected function resolveDuplicatePayloadExcludedAttributes(
		ProductPackageBaseRowModel $row,
		Model $source,
		string $relationKey,
	) : array
	{
		$relationConfig = $this->getDuplicateRelationConfig($relationKey);

		if (($relationConfig['handler'] ?? null) !== 'rows')
			return [];

		$excluded = DuplicateRelationConfigHelper::resolveDuplicateRowExcludedAttributes($relationConfig);

		if (! $row->extraFields)
			return $excluded['row'];

		$extraFieldsTable = $row->extraFields->getTable();

		if ($source->getTable() === $extraFieldsTable)
			return $excluded['extraFields'];

		if ($source->getTable() === $row->getTable())
			return $excluded['row'];

		return [];
	}

	protected function rawInsertRow(ProductPackageBaseRowModel $row, string $relationKey) : string
	{
		$newId = (string) Str::uuid();

		$payload = $this->buildRawInsertPayload($row, [
			'id' => $newId,
			$row->getModelContainerRelationName() . '_id' => $this->newRowContainer->getKey(),
		], $row, $relationKey);

		DB::table($row->getTable())->insert($payload);

		return $newId;
	}

	protected function rawInsertExtraFields(ProductPackageBaseRowModel $row, string $newRowId, string $relationKey) : void
	{
		if (! $row->extraFields)
			return;

		$extraFields = $row->extraFields;

		$payload = $this->buildRawInsertPayload($extraFields, [
			'id' => (string) Str::uuid(),
			$row->extraFields()->getForeignKeyName() => $newRowId,
		], $row, $relationKey);

		DB::table($extraFields->getTable())->insert($payload);
	}

	public function duplicateRow(string $relation, ProductPackageBaseRowModel $row) : string
	{
		$newRowId = $this->rawInsertRow($row, $relation);

		$this->rawInsertExtraFields($row, $newRowId, $relation);

		return $newRowId;
	}

	public function recalculateParametersByForm()
	{
		//
	}

	public function duplicate()
	{
		$this->duplicateRowContainer();

		$this->duplicateSelectedRows();

		$this->duplicateConfiguredRelations();

		$this->recalculateParametersByForm();

		$this->newRowContainer->load('rows.extraFields');

		return $this->newRowContainer;
	}
}
