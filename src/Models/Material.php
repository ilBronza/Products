<?php

namespace IlBronza\Products\Models;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Interfaces\SellableSupplierPriceCreatorBaseClass;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSellableTrait;

class Material extends ProductPackageBaseModel implements SellableItemInterface
{
	use InteractsWithSellableTrait;

	static $modelConfigPrefix = 'material';

	public function getPriceCreator() : ?SellableSupplierPriceCreatorBaseClass
	{
		dd('qua, configurare un creatore da config in modo che ogni progetto abbia il suo');

		return new MaterialPricesCreatorHelper;
	}

	public function getPriceFieldsForSellable() : array
	{
		return [];
	}
}