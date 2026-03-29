<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\PriceCreatorHelpers\SellablePricesCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use Illuminate\Support\Collection;

class SellableCreatorHelper
{
	static function getOrcreateSellableByTarget(SellableItemInterface $target, null|Collection|array $categories = [], string $type = null) : Sellable
	{
		if ( $sellable = static::getSellableByTarget($target, $type))
			return $sellable;

		$sellable = static::createSellableByTarget($target, $type);

		$sellable->categories()->syncWithoutDetaching($categories);

		SellablePricesCreatorHelper::calculatePricesBySellable($sellable, $target);

		SellableSupplierCreatorHelper::setSellableSuppliersBySellable($sellable);

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
}