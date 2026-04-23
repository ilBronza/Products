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
	public function addFieldsToUpdateByRowTypes(string $trowTypes)
	{
		$fieldsToUpdateOnTableEdit = [];

		foreach (RowsCostsFieldsHelper::getRowCostsFieldsByRelation($trowTypes) as $field)
			$fieldsToUpdateOnTableEdit[] = $field;

		$this->fieldsToUpdateOnTableEdit = array_merge($this->fieldsToUpdateOnTableEdit, $fieldsToUpdateOnTableEdit);
	}

	public function addSummaryFieldsCastsByRowTypes(string $trowTypes)
	{
		$rowTypesFieldName = Str::snake($trowTypes);

		$casts = [
			"total_{$rowTypesFieldName}_revenue" => CalculatedTotalRevenueExtraField::class . ':' . $trowTypes,
			"total_{$rowTypesFieldName}_cost" => CalculatedTotalCostExtraField::class . ':' . $trowTypes,
			"margin_{$rowTypesFieldName}" => CalculatedTotalMarginExtraField::class . ':' . $trowTypes,
			"percentage_margin_{$rowTypesFieldName}" => CalculatedTotalPercentageMarginExtraField::class . ':' . $trowTypes,
		];

		$this->casts = array_merge($this->casts, $casts);

	}

	public function getTotalByCustomRowsCost(string $customRowsType)
	{
		return $this->$customRowsType->sum(function($item)
		{
			if (! $item->isCostApproved())
				return 0;

			return $item->calculated_total_row_cost;
		});
	}

	public function getTotalByCustomRowsRevenue(string $customRowsType)
	{
		return $this->$customRowsType->sum(function($item)
		{
			if (! $item->isRevenueApproved())
				return 0;

			return $item->calculated_total_row_revenue;
		});
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