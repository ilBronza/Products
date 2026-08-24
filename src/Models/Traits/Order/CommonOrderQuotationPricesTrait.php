<?php

namespace IlBronza\Products\Models\Traits\Order;

use IlBronza\Products\Casts\CalculatedTotalCostExtraField;
use IlBronza\Products\Casts\CalculatedTotalMarginExtraField;
use IlBronza\Products\Casts\CalculatedTotalPercentageMarginExtraField;
use IlBronza\Products\Casts\CalculatedTotalRevenueExtraField;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsCostsFieldsHelper;
use Illuminate\Support\Str;

trait CommonOrderQuotationPricesTrait
{
	public function setRowRelationsParameters(string $rowTypes, bool $silent = false)
	{
		$this->addRowTypeRelations($rowTypes);

		if(! $silent)
			$this->addFieldsToUpdateByRowTypes($rowTypes);

		$this->addSummaryFieldsCastsByRowTypes($rowTypes);
	}

	public function addFieldsToUpdateByRowTypes(string $rowTypes)
	{
		$fieldsToUpdateOnTableEdit = [];

		foreach (RowsCostsFieldsHelper::getRowCostsFieldsByRelation($rowTypes) as $field)
			$fieldsToUpdateOnTableEdit[] = $field;

		$this->fieldsToUpdateOnTableEdit = array_merge($this->fieldsToUpdateOnTableEdit, $fieldsToUpdateOnTableEdit);
	}

	/***
	 * total_hotel_rows_cost - total_hotel_rows_revenue
	 * total_vehicle_rows_revenue - total_operator_rows_revenue - total_product_rows_revenue - total_accessory_rows_revenue - total_production_rows_revenue
	 * total_vehicle_rows_cost - total_operator_rows_cost - total_product_rows_cost - total_accessory_rows_cost - total_production_rows_cost
	 * margin_vehicle_rows - margin_operator_rows - margin_product_rows - margin_accessory_rows - margin_production_rows
	 * percentage_margin_vehicle_rows - percentage_margin_operator_rows - percentage_margin_product_rows - percentage_margin_accessory_rows - percentage_margin_production_rows
	 ***/
	public function addSummaryFieldsCastsByRowTypes(string $rowTypes)
	{
		$rowTypesFieldName = Str::snake($rowTypes);

		$casts = [
			"total_{$rowTypesFieldName}_revenue" => CalculatedTotalRevenueExtraField::class . ':' . $rowTypes,
			"total_{$rowTypesFieldName}_cost" => CalculatedTotalCostExtraField::class . ':' . $rowTypes,
			"margin_{$rowTypesFieldName}" => CalculatedTotalMarginExtraField::class . ':' . $rowTypes,
			"percentage_margin_{$rowTypesFieldName}" => CalculatedTotalPercentageMarginExtraField::class . ':' . $rowTypes,
		];

		$this->casts = array_merge($this->casts, $casts);

	}

	public function getTotalByCustomRowsCost(string $customRowsType)
	{
		// return cache()->remember(
		// 	$this->cacheKey('getTotalByCustomRowsCost' . $customRowsType),
		// 	3600,
		// 	function() use($customRowsType)
		// 	{
				return $this->$customRowsType->sum(function($item)
				{
					if (! $item->isCostApprovedForTotals())
						return 0;

					return $item->calculated_total_row_cost;
				});				
		// 	}
		// );
	}

	//total_product_rows_revenue
	public function getTotalByCustomRowsRevenue(string $customRowsType)
	{
		return cache()->remember(
			$this->cacheKey('getTotalByCustomRowsRevenue' . $customRowsType),
			3600,
			function() use($customRowsType)
			{
				return $this->$customRowsType->sum(function($item)
				{
					if (! $item->isRevenueApproved())
						return 0;

					return $item->calculated_total_row_revenue;
				});
			}
		);
	}

	public function getMarginByCustomRows(string $customRowsType)
	{
		return $this->getTotalByCustomRowsRevenue($customRowsType) - $this->getTotalByCustomRowsCost($customRowsType);
	}

	public function getPercentageMarginByCustomRows(string $customRowsType)
	{
		if(! $revenue = $this->getTotalByCustomRowsRevenue($customRowsType))
			return 0;

		return round($this->getMarginByCustomRows($customRowsType) / $revenue * 100, 2);
	}	
}