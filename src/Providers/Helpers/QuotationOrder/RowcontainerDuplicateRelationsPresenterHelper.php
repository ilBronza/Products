<?php

namespace IlBronza\Products\Providers\Helpers\QuotationOrder;

use Carbon\Carbon;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

use function array_key_exists;
use function config;
use function method_exists;
use function trans;

class RowcontainerDuplicateRelationsPresenterHelper
{
	public function __construct(public ProductPackageBaseRowcontainerModel $rowContainer)
	{
	}

	public function getRelationsBlocks() : array
	{
		$blocks = [];

		foreach ($this->getDuplicateRelationsConfig() as $key => $relationConfig)
			$blocks[] = $this->buildRelationBlock($key, $relationConfig);

		return $blocks;
	}

	public function getDuplicateRelationsConfig() : array
	{
		$fromConfig = config('products.models.' . $this->rowContainer::$modelConfigPrefix . '.duplicateRelations');

		return DuplicateRelationConfigHelper::normalizedDuplicateRelationsMap(
			is_array($fromConfig) ? $fromConfig : []
		);
	}

	protected function buildRelationBlock(string $key, array $relationConfig) : array
	{
		$relationName = $relationConfig['relation'] ?? $key;
		$multiple = $this->resolveMultipleForRelation($relationName, $relationConfig);
		$instances = $this->loadRelationInstances($relationName, $relationConfig, $multiple);

		return [
			'key' => $key,
			'label' => $this->resolveLabel($relationConfig, $key),
			'multiple' => $multiple,
			'defaultSelected' => $relationConfig['default'] ?? true,
			'columns' => $this->normalizeColumns($relationConfig['columns'] ?? []),
			'instances' => $instances->map(fn (Model $instance) => [
				'id' => $instance->getKey(),
				'label' => $this->resolveInstanceLabel($instance, $relationConfig),
				'cells' => $this->resolveInstanceCells($instance, $relationConfig['columns'] ?? []),
			])->values()->all(),
		];
	}

	protected function resolveMultipleForRelation(string $relationName, array $relationConfig) : bool
	{
		if (! method_exists($this->rowContainer, $relationName))
			return true;

		$relation = $this->rowContainer->$relationName();

		if (! $relation instanceof Relation)
			return true;

		$explicit = array_key_exists('multiple', $relationConfig)
			? (bool) $relationConfig['multiple']
			: null;

		return DuplicateRelationConfigHelper::resolveMultiple($relation, $explicit);
	}

	protected function loadRelationInstances(string $relationName, array $relationConfig, bool $multiple) : Collection
	{
		if (! method_exists($this->rowContainer, $relationName))
			return collect();

		$relation = $this->rowContainer->$relationName();

		if (! $relation instanceof Relation)
			return collect();

		$query = $relation->getQuery();

		if ($eagerLoad = $relationConfig['eagerLoad'] ?? null)
			$query->with($eagerLoad);

		if ($multiple)
			return $query->get();

		$instance = $query->first();

		return $instance ? collect([$instance]) : collect();
	}

	protected function resolveLabel(array $relationConfig, string $key) : string
	{
		if ($label = $relationConfig['label'] ?? null)
			return trans($label);

		$rowsLangKey = 'products::rows.' . $key;

		if (Lang::has($rowsLangKey))
			return trans($rowsLangKey);

		$prefix = $this->rowContainer::$modelConfigPrefix;

		return trans('products::' . $prefix . 's.duplicateRelations.' . $key);
	}

	protected function normalizeColumns(array $columns) : array
	{
		return array_map(function (array $column)
		{
			if (isset($column['label']))
				$column['label'] = trans($column['label']);
			else
			{
				$suffix = DuplicateRelationConfigHelper::inferColumnLangKeySuffix($column);

				$column['label'] = $suffix !== ''
					? trans('products::fields.' . $suffix)
					: '';
			}

			return $column;
		}, $columns);
	}

	protected function resolveInstanceLabel(Model $instance, array $relationConfig) : string
	{
		if ($method = $relationConfig['instanceLabelMethod'] ?? null)
			if (method_exists($instance, $method))
				return (string) $instance->$method();

		if ($attribute = $relationConfig['instanceLabelAttribute'] ?? null)
			return (string) ($instance->$attribute ?? $instance->getKey());

		if ($description = $instance->description ?? null)
			return (string) $description;

		return (string) $instance->getKey();
	}

	protected function resolveInstanceCells(Model $instance, array $columns) : array
	{
		if (! $columns)
			return [['value' => $this->resolveInstanceLabel($instance, [])]];

		return array_map(fn (array $column) => [
			'value' => $this->resolveColumnValue($instance, $column),
		], $columns);
	}

	protected function resolveColumnValue(Model $instance, array $column) : ?string
	{
		if ($method = $column['method'] ?? null)
			if (method_exists($instance, $method))
				$value = $instance->$method();
			else
				$value = null;
		else
			$value = $instance->{$column['attribute']} ?? null;

		if ($value instanceof Carbon)
			return $value->format(($column['type'] ?? null) === 'datetime' ? 'd/m/Y H:i' : 'd/m/Y');

		if ($value === null || $value === '')
			return '—';

		return (string) $value;
	}
}
