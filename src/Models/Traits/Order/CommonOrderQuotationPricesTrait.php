<?php

namespace IlBronza\Products\Models\Traits\Order;

use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsCostsFieldsHelper;

trait CommonOrderQuotationPricesTrait
{
	public function addFieldsToUpdateByRowTypes(string $trowTypes)
	{
		$fieldsToUpdateOnTableEdit = [];

		foreach (RowsCostsFieldsHelper::getRowCostsFieldsByRelation($trowTypes) as $field)
			$fieldsToUpdateOnTableEdit[] = $field;

		$this->fieldsToUpdateOnTableEdit = array_merge($this->fieldsToUpdateOnTableEdit, $fieldsToUpdateOnTableEdit);
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