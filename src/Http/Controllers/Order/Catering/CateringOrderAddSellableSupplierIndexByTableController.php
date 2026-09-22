<?php

namespace IlBronza\Products\Http\Controllers\Order\Catering;

use IlBronza\Products\Http\Controllers\Order\OrderAddSellableSupplierIndexByTableController;
use IlBronza\Products\Models\Order;

class CateringOrderAddSellableSupplierIndexByTableController extends OrderAddSellableSupplierIndexByTableController
{
	public function getIndexElements()
	{
		$getterMethod = "get{$this->type}SellableSuppliers";

		if(method_exists(Order::gpc(), $getterMethod))
		{
			app('uikittemplate')->addCustomGetter($getterMethod);

			return Order::gpc()::$getterMethod();
		}

		$result = $this->getModelClass()::query()
			->whereHas('sellable', function($query)
			{
				$query->byType($this->type);
				$query->whereNull('deleted_at');
			})
			->with([
				'supplier.target',
				'sellable.target.media.model',
				'sellable.target.allergens',
				'sellable.target.descendants.allergens',
				'sellable.target.descendants.descendants.allergens',
				'sellable.target.categories',
				'prices',
			])
			->take(10)
			->get();

		return $result;
	}

}
