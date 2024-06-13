<?php

namespace IlBronza\Products\Models\Interfaces;

use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Providers\PriceCreatorHelper;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class SellableSupplierPriceCreatorBaseClass
{
	public SellableSupplier $sellableSupplier;
	public Sellable $sellable;
	public Supplier $supplier;

	public function setModel(SellableSupplier $sellableSupplier)
	{
		$this->sellableSupplier = $sellableSupplier;

		$this->sellable = $sellableSupplier->getSellable();
		$this->supplier = $sellableSupplier->getSupplier();
	}

	public function getModel() : SellableSupplier
	{
		return $this->sellableSupplier;
	}

	public function createPrice() : Price
	{
		return (new PriceCreatorHelper($this->getModel()))
                ->createPrice();
	}

	abstract public function createPrices() : Collection;
}