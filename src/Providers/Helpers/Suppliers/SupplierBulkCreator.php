<?php

namespace IlBronza\Products\Providers\Helpers\Suppliers;

use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;
use Illuminate\Support\Collection;

abstract class SupplierBulkCreator
{
	abstract public function getPossibleSuppliersTarget() : Collection;

	static function execute() : true
	{
		$helper = new static();

		$elements = $helper->getPossibleSuppliersTarget();

		foreach($elements as $element)
			cache()->remember(
				$element->cacheKey('SupplierBulkCreator_execute'),
				3600,
				function() use($element)
				{
					return SupplierCreatorHelper::getOrCreateSupplierFromTarget($element);
				}
			);

		return true;
	}
}