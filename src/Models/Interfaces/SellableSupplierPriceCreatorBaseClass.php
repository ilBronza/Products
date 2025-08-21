<?php

namespace IlBronza\Products\Models\Interfaces;

use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Providers\PriceCreatorHelper;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Ukn\Ukn;
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

	public function getSellableSupplier() : SellableSupplier
	{
		return $this->sellableSupplier;
	}

	public function getSupplier() : Supplier
	{
		return $this->supplier;
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function getModel() : SellableSupplier
	{
		return $this->sellableSupplier;
	}

	public function getOrCreatePriceByCollectionId(string $collectionId) : Price
	{
		if($price = $this->getPriceByCollectionId($collectionId))
			return $price;

		$price = $this->createPrice($collectionId);
		$price->setCollectionId($collectionId);

		return $price;
	}

	public function getPriceByCollectionId(string $collectionId) : ? Price
	{
		return $this->getModel()
			->getPriceByCollectionId($collectionId);
	}

	public function createPrice(string $collectionId = null) : Price
	{
		if($collectionId)
			Ukn::s(trans('prices::prices.createdBy', [
					'collection' => $collectionId,
						'model' => $this->getModel()->getSupplier()->getName()
					]));
		else
			Ukn::s(trans('prices::prices.createdFor', [
					'model' => $this->getModel()->getSupplier()->getName()
				]));

		return (new PriceCreatorHelper($this->getModel()))
                ->createPrice();
	}

	abstract public function createPrices() : Collection;
}