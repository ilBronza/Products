<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use Exception;
use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Providers\PriceCreatorHelper;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\Sellables\SellableFinderHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierFinderHelper;
use IlBronza\Ukn\Facades\Ukn;
use Illuminate\Support\Facades\Log;

class SellableSupplierCreatorHelper
{
	static function createSellableSupplierCustomPrices(SellableSupplier $sellableSupplier, float $price) : Price
	{
		$price = (new PriceCreatorHelper($sellableSupplier))->createPrice();

		$sellableSupplier->directPrice()->associate($price);
		$sellableSupplier->save();

		return $price;
	}

	static function setSellableSuppliersBySellable(Sellable $sellable)
	{
		if (! $target = $sellable->getTarget())
			throw new Exception('manca il target per questo sellable ' . $sellable->getKey());

		foreach ($target->getPossibleSuppliersElements() as $supplier)
			static::getOrCreateSellableSupplierWithStandardPrices($supplier, $sellable);
	}

	static function getOrCreateSellableSupplierWithStandardPrices(Supplier $supplier, Sellable $sellable) : SellableSupplier
	{
		$sellableSupplier = static::getOrCreateSellableSupplier($supplier, $sellable);

		$sellableSupplier->setStandardPrices();

		return $sellableSupplier;
	}

	static function createSellableSupplier(Supplier $supplier, Sellable $sellable) : SellableSupplier
	{
		$supplier->sellables()->attach(
			$sellable->getKey(),
			[
				'deleted_at' => null
			]
		);

		Ukn::s(trans('products::sellableSuppliers.createdBy', [
					'supplier' => $supplier->getName(),
					'sellable' => $sellable->getName()
				]));

		$sellableSupplier = SellableSupplierFinderHelper::findSellableSupplier($supplier, $sellable);

		return $sellableSupplier;
	}

	static function getOrCreateSellableSupplier(Supplier $supplier, Sellable $sellable) : SellableSupplier
	{
		if($sellableSupplier = SellableSupplierFinderHelper::findSellableSupplier($supplier, $sellable))
			return $sellableSupplier;

		return static::createSellableSupplier($supplier, $sellable);
	}
}