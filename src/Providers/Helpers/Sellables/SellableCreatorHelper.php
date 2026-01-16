<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use Exception;
use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Providers\PriceCreatorHelper;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\PriceCreatorHelpers\SellablePricesCreatorHelper;
use IlBronza\Ukn\Ukn;
use Illuminate\Support\Collection;
use Throwable;
use function dd;

class SellableCreatorHelper
{
	static function createSellableSupplierCustomPrices(SellableSupplier $sellableSupplier, float $price) : Price
	{
		$price = (new PriceCreatorHelper($sellableSupplier))->createPrice();

		$sellableSupplier->directPrice()->associate($price);
		$sellableSupplier->save();

		return $price;
	}

	static function getOrcreateSellableByTarget(SellableItemInterface $target, null|Collection|array $categories = [], string $type = null) : Sellable
	{
		if ( $sellable = static::getSellableByTarget($target, $type))
			return $sellable;

		$sellable = static::createSellableByTarget($target, $type);

		$sellable->categories()->syncWithoutDetaching($categories);

		SellablePricesCreatorHelper::calculatePricesBySellable($sellable, $target);

		static::setSellableSuppliersBySellable($sellable);

		return $sellable;
	}

	static function getSellableByTarget(SellableItemInterface $target, string $type = null) : ?Sellable
	{
		$query = $target->sellables();

		if ($type)
			$query->where('type', $type);

		return $query->first();
	}

	static function createSellableByTarget(SellableItemInterface $target, string $type = null) : Sellable
	{
		$sellable = Sellable::gpc()::make();
		$sellable->name = $target->getNameForSellable();
		$sellable->target()->associate($target);
		$sellable->type = $type;
		$sellable->save();

		return $sellable;
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

		return SellableFinderHelper::findSellableSupplier($supplier, $sellable);
	}

	static function getOrCreateSellableSupplier(Supplier $supplier, Sellable $sellable) : SellableSupplier
	{
		if($sellableSupplier = SellableFinderHelper::findSellableSupplier($supplier, $sellable))
			return $sellableSupplier;

		return static::createSellableSupplier($supplier, $sellable);
	}
}