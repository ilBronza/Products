<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use Exception;
use IlBronza\CRUD\Interfaces\CalendarInterface;
use IlBronza\CRUD\Interfaces\GanttTimelineInterface;
use IlBronza\CRUD\Interfaces\TimelineInterfaces\TimelineGroupInterface;
use IlBronza\CRUD\Interfaces\TimelineInterfaces\TimelineItemInterface;
use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\CRUD\Traits\Calendar\HasCalendarTrait;
use IlBronza\CRUD\Traits\Model\CRUDModelExtraFieldsTrait;
use IlBronza\CRUD\Traits\Timeline\GanttTimelineTrait;
use IlBronza\CRUD\Traits\Timeline\IsTimelineGroupTrait;
use IlBronza\CRUD\Traits\Timeline\IsTimelineItemTrait;
use IlBronza\Category\Models\Category;
use IlBronza\Products\Models\Orders\OrderQuotationExtraFields;
use IlBronza\Products\Models\Sellables\Sellable;
use function lcfirst;

class ProductPackageBaseRowcontainerModel extends ProductPackageBaseModel implements GanttTimelineInterface, CalendarInterface, TimelineGroupInterface, TimelineItemInterface
{
	use CRUDModelExtraFieldsTrait;
	use GanttTimelineTrait;
	use HasCalendarTrait;
	use IsTimelineGroupTrait;
	use IsTimelineItemTrait;

	public function getExtraFieldsClass() : ? string
	{
		return OrderQuotationExtraFields::class;
	}

	protected $casts = [
		'date' => 'date',
		'started_at' => 'date',
		'ended_at' => 'date',
		'starts_at' => 'date',
		'ends_at' => 'date',
		'cost_coefficient' => ExtraField::class,
		'state_id' => ExtraField::class,
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

		//è diventato un metodo standard
		// if ($type == 'vehicleType')
		// 	return Sellable::gpc()::byType('vehicle')->orderBy('name')->pluck('name', 'id')->toArray();

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

	public function getAddRowByTypeUrl(string $type, bool $table = false) : string
	{
		return $this->getAddOrderrowByTypeUrl($type, $table);
	}

	public function getAddSellableSupplierRowByTypeUrl(string $type)
	{
		return $this->getKeyedRoute('addSellableSupplierRows', [
			'type' => $type,
		]);
	}

	public function getStartsAt() : ?Carbon
	{
		return $this->starts_at;
	}

	public function getEndsAt() : ?Carbon
	{
		return $this->ends_at;
	}

	public function scopeByDateRange($query, string|Carbon $startsAt, string|Carbon $endsAt)
	{
		return $query
	        ->where('starts_at', '<=', $endsAt)
	        ->where(function($_query) use($startsAt)
	        	{
	        		$_query->where('ends_at', '>=', $startsAt)->orWhereNull('ends_at');
	        	});
	}

	public function getResetRowsIndexesUrl() : string
	{
		return $this->getKeyedRoute('resetRowsIndexes');
	}

	public function getTitle() : string
	{
		return "{$this->getName()} - {$this->getClient()?->getName()}";
	}

	public function getCategoriesPossibleValuesArray() : array
	{
		$mainCategory = Category::gpc()::provideCategoryByName(config('products.models.order.rootCategoryName'));

		return $mainCategory->getSelectTreeArray();
	}

	/**
	 *
	 * END ADDING ROWS METHODS
	 *
	 */


	public function getTimelineItemRightLinks(? TimelineGroupInterface $groupModel) : array
	{
		return [
			[
				'url' => $this->getGanttUrl(),
				'target' => '_blank',
				'faIcon' => 'chart-gantt',
			],
			[
				'url' => $this->getEditUrl(),
				'target' => '_blank',
				'faIcon' => 'pen-to-square',
			]
		];
	}

	public function getTimelineItemActions(? TimelineGroupInterface $groupModel) : array
	{
		return [];
		// $result = [];

		// $result[] = [
		// 	'url' => $this->getAssignSellablesupplierUrl(),
		// 	'target' => 'iframe',
		// 	'faIcon' => 'shuffle',
		// ];

		// return $result;
	}

	public function getTimelineItemTitle(? TimelineGroupInterface $groupModel) : string
	{
		return $this->getName();
		// if($groupModel instanceof Sellable)
		// 	return $this->getSupplierName() ?? 'Nd';

		// if($groupModel instanceof Supplier)
		// 	return $this->getSellableName() ?? 'Nd';

		// $pieces = [];

		// if($value = $this->getSellableName())
		// 	$pieces[] = $value;

		// if($value = $this->getSupplierName())
		// 	$pieces[] = $value;

		// return trim(implode(' - ', $pieces)) ?? 'Nd';
	}

	public function getTimelineItemGroupId(? TimelineGroupInterface $groupModel) : string
	{
		dd($groupModel);
		return $this->getSellable()?->getKey() ?? '';
	}

	public function getDescription() : ? string
	{
		return $this->description;
	}

	public function getTimelineItemPopuptitle(? TimelineGroupInterface $groupModel) : string
	{
		$pieces = [];

		if($value = $this->getName())
			$pieces[] = $value;

		if($value = $this->getDescription())
			$pieces[] = $value;

		return trim(implode(' - ', $pieces));
	}

	public function getTimelineItemStartsAt(? TimelineGroupInterface $groupModel) : Carbon
	{
		return $this->getStartsAt() ?? Carbon::now();
	}

	public function getTimelineItemEndsAt(? TimelineGroupInterface $groupModel) : Carbon
	{
		return $this->getEndsAt() ?? Carbon::now()->addHours(4);
	}

	public function getPossibleRowsTypes()
	{
		return config('products.models.' . $this::$modelConfigPrefix . '.possibleRowTypes');
	}

	public function getTotalClientPrice()
	{
		$total = 0;

		foreach($this->getPossibleRowsTypes() as $rowTypes)
			$total += $this->$rowTypes->sum('total_client_price');

		return $total;
	}
}