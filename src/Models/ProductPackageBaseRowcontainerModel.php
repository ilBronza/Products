<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use Exception;
use IlBronza\CRUD\Interfaces\CalendarInterface;
use IlBronza\Timeline\Interfaces\GanttTimelineInterface;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use IlBronza\Timeline\Interfaces\TimelineItemInterface;
use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\CRUD\Traits\Calendar\HasCalendarTrait;
use IlBronza\Timeline\Traits\GanttTimelineTrait;
use IlBronza\Timeline\Traits\IsTimelineGroupTrait;
use IlBronza\Timeline\Traits\IsTimelineItemTrait;
use IlBronza\Category\Models\Category;
use IlBronza\Products\Models\Orders\OrderQuotationExtraFields;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsCostsFieldsHelper;
use function lcfirst;

class ProductPackageBaseRowcontainerModel extends ProductPackageBaseModel implements GanttTimelineInterface, CalendarInterface, TimelineGroupInterface, TimelineItemInterface
{
	use GanttTimelineTrait;
	use HasCalendarTrait;
	use IsTimelineGroupTrait;
	use IsTimelineItemTrait;

	protected $casts = [
		'date' => 'date',
		'started_at' => 'date',
		'ended_at' => 'date',
		'starts_at' => 'date',
		'ends_at' => 'date',
		'cost_coefficient' => ExtraField::class,
		'state_id' => ExtraField::class,
	];

	public $rowTypeRelations = [];

	public function addRowTypeRelations(string $rowTypeRelations)
	{
		$this->rowTypeRelations[] = $rowTypeRelations;
	}

	public function getRowTypeRelations() : array
	{
		return array_unique(
			$this->rowTypeRelations
		);
	}

	public function getRowTypeRelationsForTotals() : array
	{
		return $this->getRowTypeRelations();
	}

	public array $fieldsToUpdateOnTableEdit = [];

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
				if ($operator = $operatorRow->getSupplier()?->getTarget()?->getOperator())
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
		//DOGODO TODO agnosticare sta roba
		$map = [
			'controlroom' => 'controlroom',
			'contracttype' => 'operator',
			'reimbursement' => 'reimbursement',
			'vehicletype' => 'vehicle',
			'rent' => 'service',
			'service' => 'service',
			'surveillance' => 'surveillance',
			'hotel' => 'hotel',
		];

		$result = [];

		foreach($map as $rowType => $sellableType)
			$result[$rowType] = fn() : array => $this->getPossibleSellablesByType($sellableType);

		return $result;
	}

	public function getPossibleSellablesByType(string $type) : array
	{
		return Sellable::gpc()::byType($type)->orderBy('name')->pluck('name', 'id')->toArray();
	}

	public function getOrderrowsPossibleSellableTypes()
	{
		return $this->_getPossibleSellableTypes();
	}

	public function getAddRowByTypeUrl(string $type, bool $table = false) : string
	{
		$params = [
			'type' => $type,
		];

		if($table)
			$params['table'] = $table;

		return $this->getKeyedRoute('addRow', $params);
	}

	public function getAddRowBySellableUrlTemplate() : string
	{
		return $this->getKeyedRoute('addRowBySellable', [
			'sellable' => config('datatables.replace_model_id_string'),
		]);
	}

	public function getAddEmptyRowByTypeUrl(string $type) : string
	{
		return $this->getKeyedRoute('addEmptyRow', [
			'type' => $type,
		]);
	}

	public function getAddSellableSupplierRowByTypeUrl(string $type)
	{
		return $this->getKeyedRoute('addSellableSupplierRows', [
			'type' => $type,
			'table' => true
		]);
	}

	public function getAddSupplierRowByTypeUrl(string $type)
	{
		return $this->getKeyedRoute('addSupplierRows', [
			'type' => $type,
			'table' => true
		]);
	}

	//TODO rimuovere ste merdate dal package. il metodo chiamato per i bottoni deve essere fuori package
	public function getCreateParentRowByManufacturerTypeButtonUrl(string $type)
	{
		return route('hotelRows.storeAgency');
	}

	public function getAssociateOrCreateParentRowByTypeUrl(string $type)
	{
		return $this->getKeyedRoute('associateOrCreateParentRowByType', [
			'type' => $type
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

	public function getTimelineBindingDataArray() : array
	{
		dd($this->getForeignKey());
		return [

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

	public function getTotalRevenueBesidesDiscount() : float
	{
		if($this->mup_selection == 'mup_forfait')
		{
			if(($this->extraFields)&&($this->extraFields->saved_total_revenue !== $this->mup_revenue))
			{
				$this->extraFields->saved_total_revenue = $this->mup_revenue ?? 0;
				$this->extraFields->save();
			}

			return $this->mup_revenue;
		}

		$totalRevenue = 0;

		foreach($this->getRowTypeRelationsForTotals() as $rowRelation)
		{
			$fieldName = RowsCostsFieldsHelper::getRevenueFieldName($rowRelation);

			$totalRevenue += $this->$fieldName;
		}

		if($this->mup_selection == 'mup_plus_extra')
			$totalRevenue += $this->mup_revenue;

		return $totalRevenue;
	}

	public function _getTotalRevenueAttribute()
	{
		if($this->mup_selection == 'mup_forfait')
		{
			if(($this->extraFields)&&($this->extraFields->saved_total_revenue !== $this->mup_revenue))
			{
				$this->extraFields->saved_total_revenue = $this->mup_revenue ?? 0;
				$this->extraFields->save();
			}

			return $this->mup_revenue;
		}

		$totalRevenue = 0;

		foreach($this->getRowTypeRelationsForTotals() as $rowRelation)
		{
			$fieldName = RowsCostsFieldsHelper::getRevenueFieldName($rowRelation);

			$totalRevenue += $this->$fieldName;
		}

		if($this->mup_selection == 'mup_plus_extra')
			$totalRevenue += $this->mup_revenue;

		$totalRevenue -= $this->getNeatDiscount($totalRevenue);

		if($this->extraFields)
		{
			if($this->extraFields->saved_total_revenue !== $totalRevenue)
			{
				$this->extraFields->saved_total_revenue = $totalRevenue ?? 0;
				$this->extraFields->save();
			}			
		}

		return $totalRevenue;
	}

	public function getTotalRevenueAttribute()
	{
		return $this->cacheMethod('_getTotalRevenueAttribute');
	}

	public function getTotalVat()
	{
		return $this->total_vat;
	}

	public function _getTotalVatAttribute()
	{
		$totalVat = 0;

		$results = [];
		$noResults = [];

		foreach($this->getRowTypeRelationsForTotals() as $rowRelation)
		{
			foreach($this->$rowRelation as $row)
			{
				$vat = $row->getCalculatedTotalRowRevenue() * ($row->calculated_vat ?? 10) / 100;

				$totalVat += $vat;
			}
		}

		return $totalVat;
	}

	//total_vat
	public function getTotalVatAttribute()
	{
		return $this->cacheMethod('_getTotalVatAttribute');
	}

	public function getTotalRevenue()
	{
		return $this->total_revenue;
	}

	public function getTotalRevenueBesidesProducts()
	{
		return $this->getTotalRevenueBesidesDiscount() - $this->total_product_rows_revenue;
	}

	//total_cost
	public function getTotalCostAttribute()
	{
		$totalCost = 0;

		foreach($this->getRowTypeRelationsForTotals() as $rowRelation)
		{
			$fieldName = RowsCostsFieldsHelper::getCostFieldName($rowRelation);

			$totalCost += $this->$fieldName;
		}

		$totalCost += $this->mup_cost;

		if($this->extraFields)
			if($this->extraFields->saved_total_costs != $totalCost)
			{
				$this->extraFields->saved_total_costs = $totalCost;
				$this->extraFields->save();
			}

		return $totalCost;
	}

	public function getTotalCost()
	{
		return $this->total_cost;
	}

	public function getTotalMarginAttribute()
	{
		return $this->getTotalRevenue() - $this->getTotalCost();
	}

	public function getTotalMargin()
	{
		return $this->total_margin;
	}

	public function getTotalPercentageMarginAttribute()
	{
		if(! $revenue = $this->getTotalRevenue())
			return 0;

		if($revenue == 0)
			return 0;

		return round($this->getTotalMargin() / $revenue * 100, 2);
	}

	public function getTotalPercentageMargin()
	{
		return $this->total_percentage_margin;
	}

	public function getClientId() : ? string
	{
		return $this->client_id;
	}
}
