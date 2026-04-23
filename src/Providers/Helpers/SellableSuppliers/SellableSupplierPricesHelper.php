<?php

namespace IlBronza\Products\Providers\Helpers\SellableSuppliers;

use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;

class SellableSupplierPricesHelper
{
	public SellableSupplier $sellableSupplier;
	public Sellable $sellable;
	public Supplier $supplier;

	public function __construct(SellableSupplier $sellableSupplier)
	{
		$this->sellableSupplier = $sellableSupplier;
		$this->sellable = $sellableSupplier->getSellable();
		$this->supplier = $sellableSupplier->getSupplier();
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function getSupplier() : Supplier
	{
		return $this->supplier;
	}

	public function updatePrices()
	{
		$prices = $this->getSellable()->getPriceFieldsForSellable();

		$target = $this->getSupplier()->getTarget();

		foreach($prices as $price => $measurementUnit)
			$this->sellableSupplier->setPriceByCollectionId($price, $target->$price, $measurementUnit);
	}
}