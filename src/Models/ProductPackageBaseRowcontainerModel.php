<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use Exception;
use IlBronza\CRUD\Interfaces\GanttTimelineInterface;
use IlBronza\CRUD\Traits\Timeline\GanttTimelineTrait;
use IlBronza\Products\Models\Sellables\Sellable;

use function lcfirst;

class ProductPackageBaseRowcontainerModel extends ProductPackageBaseModel implements GanttTimelineInterface
{
	use GanttTimelineTrait;

	protected $casts = [
		'date' => 'date',
		'started_at' => 'date',
		'ended_at' => 'date',
		'starts_at' => 'date',
		'ends_at' => 'date',
	];

	public function scopeOpened($query)
	{
		return $query->whereHas('extraFields', function ($_query)
		{
			$_query->whereNull('status')->orWhere('status', 'opened');
		});
	}

	public function getReplicateLastRowByTypeUrl(string $type)
	{
		return $this->getReplicateLastOrderrowByTypeUrl($type);
	}

	public function getReplicateLastOrderrowByTypeUrl(string $type) : string
	{
		return $this->getKeyedRoute('replicateLastRowByType', [
			$this,
			'type' => $type
		]);
	}

	public function getPossibleOperatorsArrayValues() : array
	{
		return cache()->remember(
			$this->cacheKey('getPossibleOperatorsArrayValues'), 10, function ()
		{
			$result = [];

			foreach ($this->operatorRows()->with('sellableSupplier.supplier.target')->get() as $operatorRow)
				if ($operator = $operatorRow->getSupplier()?->getTarget())
					$result[$operator->getKey()] = $operator->getName();

			asort($result);

			return $result;
		}
		);
	}

	/**
	 *
	 * START ADDING ROWS METHODS
	 *
	 */

	public function _getPossibleSellableTypes()
	{
		return [
			'controlroom' => function () : array
			{
				return Sellable::gpc()::byType('controlroom')->orderBy('name')->pluck('name', 'id')->toArray();
			},

			'contracttype' => function () : array
			{
				return Sellable::gpc()::byType('operator')->orderBy('name')->pluck('name', 'id')->toArray();
			},

			'reimbursement' => function () : array
			{
				return Sellable::gpc()::byType('reimbursement')->orderBy('name')->pluck('name', 'id')->toArray();
			},

			'vehicletype' => function () : array
			{
				return Sellable::gpc()::byType('vehicle')->orderBy('name')->pluck('name', 'id')->toArray();
			},

			'rent' => function () : array
			{
				return Sellable::gpc()::byType('service')->orderBy('name')->pluck('name', 'id')->toArray();
				//				return Sellable::gpc()::gpc()::getServices();
			},

			'service' => function () : array
			{
				return Sellable::gpc()::byType('service')->orderBy('name')->pluck('name', 'id')->toArray();
				//				return Sellable::gpc()::gpc()::getServices();
			},

			'surveillance' => function () : array
			{
				return Sellable::gpc()::byType('surveillance')->orderBy('name')->pluck('name', 'id')->toArray();
				//				$category = Category::getProjectClassName()::findCachedField('name', 'Sorveglianza');
				//
				//				return Sellable::gpc()::byCategory($category)->orderBy('name')->pluck('name', 'id')->toArray();
			},

			'hotel' => function () : array
			{
				return Sellable::gpc()::byType('hotel')->orderBy('name')->pluck('name', 'id')->toArray();
				//				$category = Category::getProjectClassName()::findCachedField('name', 'Stanze albergo');
				//
				//				return Sellable::byCategory($category)->orderBy('name')->pluck('name', 'id')->toArray();
			}
		];
	}

	public function getPossibleSellablesByType(string $type) : array
	{
		$type = lcfirst($type);

		if ($type == 'contracttype')
			return Sellable::gpc()::byType('operator')->orderBy('name')->pluck('name', 'id')->toArray();

		if ($type == 'controlroom')
			return Sellable::gpc()::byType('controlroom')->orderBy('name')->pluck('name', 'id')->toArray();

		if ($type == 'reimbursement')
			return Sellable::gpc()::byType('reimbursement')->orderBy('name')->pluck('name', 'id')->toArray();

		if ($type == 'vehicleType')
			return Sellable::gpc()::byType('vehicle')->orderBy('name')->pluck('name', 'id')->toArray();

		if ($type == 'rent')
			return Sellable::gpc()::byType('service')->orderBy('name')->pluck('name', 'id')->toArray();

		if ($type == 'service')
			return Sellable::gpc()::byType('service')->orderBy('name')->pluck('name', 'id')->toArray();

		if ($type == 'surveillance')
			return Sellable::gpc()::byType('surveillance')->orderBy('name')->pluck('name', 'id')->toArray();

		if ($type == 'hotel')
			return Sellable::gpc()::byType('hotel')->orderBy('name')->pluck('name', 'id')->toArray();

		return Sellable::gpc()::byType($type)->orderBy('name')->pluck('name', 'id')->toArray();
//		throw new Exception("Type $type not found");
	}

	public function getOrderrowsPossibleSellableTypes()
	{
		return $this->_getPossibleSellableTypes();
	}

	public function getAddRowByTypeUrl(string $type, bool $table = false)
	{
		return $this->getAddOrderrowByTypeUrl($type, $table);
	}

	public function getStartsAt() : ?Carbon
	{
		return $this->starts_at;
	}

	public function getEndsAt() : ?Carbon
	{
		return $this->ends_at;
	}

	public function getResetRowsIndexesUrl() : string
	{
		return $this->getKeyedRoute('resetRowsIndexes');
	}

	/**
	 *
	 * END ADDING ROWS METHODS
	 *
	 */

}