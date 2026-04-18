<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\Products\Models\Order;

trait CommonOrderrowQuotationrowPricesTrait
{
	public function initializeCommonOrderrowQuotationrowPricesTrait()
	{
		$casts = [
			'stored_total_row_cost' => ExtraField::class,
			'stored_total_row_revenue' => ExtraField::class,

			'approved_total_row_cost' => ExtraField::class,
			'approved_total_row_revenue' => ExtraField::class,
		];

		$this->casts = array_merge($this->casts, $casts);
	}

	public function getCostCoefficient() : float
	{
		return $this->cost_coefficient;
	}

	public function getCostCoefficientAttribute($value) : float
	{
		return $value ?? $this->getModelContainer()?->getCostCoefficient() ?? 1;
	}

	public function setCalculatedTotalRowCostAttribute($value)
	{
		return $this->setCalculateOverrideablePriceValue('total_row_cost', $value);
	}

	//calculated_total_row_cost
	public function getCalculatedTotalRowCostAttribute() : float
	{
		return $this->getCalculateOverrideablePriceValue('total_row_cost');
	}

	public function setCalculatedTotalRowRevenueAttribute($value)
	{
		return $this->setCalculateOverrideablePriceValue('total_row_revenue', $value);
	}

	//calculated_total_row_revenue
	public function getCalculatedTotalRowRevenueAttribute() : float
	{
		return $this->getCalculateOverrideablePriceValue('total_row_revenue');
	}

	//total_row_cost
	public function getTotalRowCost() : ? float
	{
		return $this->total_row_cost;
	}

	//row_cost
	public function getRowCost() : float
	{
		return $this->row_cost;
	}

	public function getRowCostAttribute() : float
	{
		return round($this->getQuantity() * $this->getCalculatedSingleCost(), 2) ?? 0;
	}

	public function getCalculatedSingleCost() : ? float
	{
		return $this->calculated_single_cost;
	}

	public function getTotalRowRevenueAttribute() : float
	{
		return $this->getRowRevenue();
	}

	public function getRowRevenue() : float
	{
		return $this->row_revenue;
	}

	public function getTotalRowRevenue() : ? float
	{
		return $this->total_row_revenue;
	}

	public function getRowRevenueAttribute($value) : float
	{
		if($value)
			return $value;

		return round($this->getQuantity() * $this->getCalculatedSingleRevenue(), 2) ?? 0;
	}

	public function getCalculatedSingleRevenue()
	{
		return $this->calculated_single_revenue;
	}

	public function getCalculateOverrideablePriceValue(string $priceName) : ? float
	{
		$fieldName = 'stored_' . $priceName;
		
		if($this->$fieldName)
			return $this->$fieldName;

		if($this->$priceName)
			return $this->$priceName;

		if($value = $this->getSellableSupplier()?->$priceName)
			return $value;

		if($value = $this->getSellable()?->$priceName)
			return $value;

		return 0;
	}

	public function setCalculateOverrideablePriceValue(string $priceName, ? float $value)
	{
		$fieldName = 'stored_' . $priceName;

		$this->$fieldName = $value;
	}


}