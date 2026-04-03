<?php

namespace IlBronza\Products\Models\Traits\Sellable;

trait SingleSellableSupplierTrait
{
	protected static function bootSingleSellableSupplierTrait()
	{
		// static::saving(function ($model)
		// {
		// 	dd($model);
		// 	// $supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($model);

		// 	// $possibleSellables = $model->getPossibleSellables();

		// 	// foreach($possibleSellables as $possibleSellable)
		// 	// 	$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $possibleSellable);
		// });

		// static::deleting(function ($model)
		// {
		// 	if($supplier = $model->getSupplier())
		// 		$supplier->delete();
		// });
	}
}