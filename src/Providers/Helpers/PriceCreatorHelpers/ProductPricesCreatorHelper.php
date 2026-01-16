<?php

namespace IlBronza\Products\Providers\Helpers\PriceCreatorHelpers;

use IlBronza\Products\Models\Interfaces\SellableSupplierPriceCreatorBaseClass;

class ProductPricesCreatorHelper extends SellableSupplierPriceCreatorBaseClass
{
	public Supplier $supplier;

	public function __construct(Client $client)
	{
		die('uccidere questo tutto da rifare');
		dd($client);

		$this->supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($client);

		$this->createPrices();
	}

	static function checkVideoSupplierCompletion(Client $client) : Supplier
	{
		$helper = new static($client);

		return $helper->getSupplier();
	}

	public function getSupplier() : Supplier
	{
		return $this->supplier;
	}

	public function createPrices() : Collection
	{
		$prices = collect();
		//
		//		dd('qua');
		//		foreach (['Servizi generici passati'] as $name)
		//		{
		//			$sellable = Sellable::gpc()::where('name', $name)->first();
		//			$sellableSupplier = SellableCreatorHelper::getOrCreateSellableSupplier($this->getSupplier(), $sellable);
		//
		//			if (! $price = $sellableSupplier->getPriceByCollectionId('costCompanyDay'))
		//			{
		//				$price = Price::gpc()::make();
		//
		//				$price->save();
		//
		//				$price->setMeasurementUnit('day', false);
		//				$price->setCollectionId('costCompanyDay');
		//
		//				$sellableSupplier->directPrice()->associate($price);
		//				$sellableSupplier->prices()->save($price);
		//
		//				$sellableSupplier->save();
		//			}
		//
		//			$prices->push($price);
		//		}

		return $prices;
	}
}