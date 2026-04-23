<?php

namespace IlBronza\Products\Models\Sellables\Helpers;

use IlBronza\Products\Providers\Helpers\SellableSuppliers\SellableSupplierPricesHelper;

class ProductClientSellableSupplierPricesHelper extends SellableSupplierPricesHelper
{
	public function updatePrices()
	{
		$prices = $this->getSellable()->getPriceFieldsForSellable();

		$target = $this->getSellable()->getTarget();

		foreach($prices as $price => $measurementUnit)
			$this->sellableSupplier->setPriceByCollectionId($price, $target->$price, $measurementUnit);
	}
}
