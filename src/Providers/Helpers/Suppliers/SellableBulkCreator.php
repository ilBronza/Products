<?php

namespace IlBronza\Products\Providers\Helpers\Suppliers;

use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use Illuminate\Support\Collection;

abstract class SellableBulkCreator
{
	abstract public function getPossibleSellablesTarget() : Collection;

	static function execute() : true
	{
		$helper = new static();

		$elements = $helper->getPossibleSellablesTarget();

		foreach($elements as $element)
			cache()->remember(
				$element->cacheKey('SellableBulkCreator_execute_meeeeh'),
				3600,
				function() use($element)
				{
					return SellableCreatorHelper::getOrCreateSellableByTarget($element, null, $element->getSellableTypeName());
				}
			);

		return true;
	}
}