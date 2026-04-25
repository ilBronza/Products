<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\CRUD\Models\Casts\CastFieldPrice;
use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\Products\Models\Accessory;

trait AccessoryRowQuotationOrderCommonTrait
{
	public function initializeAccessoryRowQuotationOrderCommonTrait()
	{
		$this->setCustomrowCasts(
			Accessory::gpc()::make()->getPriceFieldsForSellable()
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

	public function getTimeQuantity() : float
	{
		return 1;
	}

	public function getTimeCost() : float
	{
		return $this->calculated_cost_per_hour;
	}

	public function getTimeRevenue() : float
	{
		return $this->calculated_revenue_per_hour;
	}

	//single_cost
	public function getSingleCostAttribute() : float
	{
		$timeCost = $this->getTimeQuantity() * $this->getTimeCost();

		$movimentationCost = $this->calculated_cost_per_movimentation;

		return $timeCost + $movimentationCost;
	}

	//single_revenue
	public function getSingleRevenueAttribute() : float
	{
		$timeCost = $this->getTimeQuantity() * $this->getTimeRevenue();

		$movimentationCost = $this->calculated_revenue_per_movimentation;

		return $timeCost + $movimentationCost;
	}

	//total_row_cost
	public function getTotalRowCostAttribute() : float
	{
		return $this->getCalculatedSingleCost() * $this->getQuantity() * $this->getCostCoefficient();
	}

	//total_row_revenue
	public function getTotalRowRevenueAttribute() : float
	{
		return $this->getCalculatedSingleRevenue() * $this->getQuantity() * $this->getRevenueCoefficient();
	}

}