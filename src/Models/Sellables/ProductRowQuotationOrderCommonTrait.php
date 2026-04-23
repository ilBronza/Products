<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\CRUD\Models\Casts\CastFieldPrice;
use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\Products\Models\Product\Product;

trait ProductRowQuotationOrderCommonTrait
{
	public function initializeProductRowQuotationOrderCommonTrait()
	{
		$prices = Product::gpc()::make()->getPriceFieldsForSellable();

		$this->setCustomrowCasts(
			$prices
		);

		$this->setCustomrowCasts(
			[
				'quantity_coefficient' => '',
				'cost_coefficient' => '',
				'revenue_coefficient' => ''
			]
		);

		// $casts = [];

		// foreach ($prices as $field => $measurementUnit)
		// 	$casts[$field] = ExtraField::class . ":sellableSupplier";

		// $this->casts = array_merge($this->casts, $casts);
	}

	public function getSingleCostAttribute() : float
	{
		return $this->single_revenue * 0.7;
	}

	public function getSingleRevenueAttribute() : float
	{
		return $this->getSellableSupplier()->single_revenue;
	}

	//total_row_cost
	public function getTotalRowCostAttribute() : float
	{
		return $this->getCalculatedSingleCost() * $this->getQuantity() * $this->getCostCoefficient();
	}

	//total_row_revenue
	public function getTotalRowRevenueAttribute() : float
	{
		//dd($this->single_revenue, $this->getQuantity(), $this->getRevenueCoefficient());
		return $this->getCalculatedSingleRevenue() * $this->getQuantity() * $this->getRevenueCoefficient();
	}

}