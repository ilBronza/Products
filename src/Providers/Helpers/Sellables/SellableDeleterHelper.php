<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;

class SellableDeleterHelper
{
	static function deleteSellableSupplierBySellableSupplierModels(Sellable $sellable, Supplier $supplier)
	{
		if(! $sellableSupplier = SellableFinderHelper::findSellableSupplier($supplier, $sellable))
			return ;

		$sellableSupplier->delete();
	}

	static function deleteSellableSupplierByTargets()
	{

	}
}