<?php

namespace IlBronza\Products\Providers\Helpers\PriceCreatorHelpers;

use IlBronza\Prices\Models\Interfaces\WithPriceInterface;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;

class SellablePricesCreatorHelper
{
	static function calculatePricesByTarget(WithPriceInterface $target) : true
	{
		$sellable = SellableCreatorHelper::getOrcreateSellableByTarget($target);

		return static::calculatePricesBySellable($sellable, $target);
	}

	static function calculatePricesBySellable($sellable, $target = null) : true
	{
		if(! $target)
			$target = $sellable->getTarget();

		foreach($target->getPriceBaseAttributes() as $name => $value)
			$sellable->$name = $value;

		return $sellable->save();
	}
}