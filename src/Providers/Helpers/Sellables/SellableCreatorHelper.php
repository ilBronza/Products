<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use Exception;
use IlBronza\Prices\Models\Price;
use IlBronza\Prices\Providers\PriceCreatorHelper;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use Illuminate\Support\Collection;

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

	static function getOrcreateSellableByTarget(SellableItemInterface $target, Collection|array $categories = [], string $type = null) : Sellable
	{
		if (! $sellable = static::getSellableByTarget($target, $type))
			$sellable = static::createSellableByTarget($target, $type);

		static::setSellableSuppliersBySellable($sellable);

		$sellable->categories()->syncWithoutDetaching($categories);

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
		$sellable = Sellable::getProjectClassName()::make();
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
			static::createSellableSupplierWithStandardPrices($supplier, $sellable);
	}

	static function createSellableSupplierWithStandardPrices(Supplier $supplier, Sellable $sellable) : SellableSupplier
	{
		$sellableSupplier = static::createSellableSupplier($supplier, $sellable);

		$sellableSupplier->setStandardPrices();

		return $sellableSupplier;
	}

	static function createSellableSupplier(Supplier $supplier, Sellable $sellable) : SellableSupplier
	{
		$supplier->sellables()->syncWithoutDetaching(
			$sellable->getKey()
		);

		return static::getSellableSupplier($supplier, $sellable);
	}

	static function getOrCreateSellableSupplier(Supplier $supplier, Sellable $sellable) : SellableSupplier
	{
		if($sellableSupplier = SellableSupplier::where('sellable_id', $sellable->getKey())
		                                       ->where('supplier_id', $supplier->getKey())
		                                       ->first())
			return $sellableSupplier;

		return static::createSellableSupplier($supplier, $sellable);
	}

	static function getSellableSupplier(Supplier $supplier, Sellable $sellable) : ?SellableSupplier
	{
		return $supplier->sellableSuppliers()->where('sellable_id', $sellable->getKey())->first();
	}
}