<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;

class SellableFinderHelper
{
	static function findSellableSupplier(Supplier $supplier, Sellable $sellable) : ? SellableSupplier
	{
		return SellableSupplier::gpc()::where('sellable_id', $sellable->getKey())
		                              ->where('supplier_id', $supplier->getKey())
		                              ->first();
	}

	static function findSellableSupplierByTargets(SellableItemInterface $sellableTarget, SupplierInterface $supplierTarget) : ? SellableSupplier
	{
		if(! $supplier = $supplierTarget->getSupplier())
			return null;

		if(! $sellable = SellableCreatorHelper::getSellableByTarget($sellableTarget))
			return null;

		return static::findSellableSupplier(
			$sellable,
			$supplier
		);
	}
}