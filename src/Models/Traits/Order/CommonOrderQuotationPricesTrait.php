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
	public function setRowRelationsParameters(string $rowTypes)
	{
		$this->addRowTypeRelations($rowTypes);
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