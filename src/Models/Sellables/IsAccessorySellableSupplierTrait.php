<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\CRUD\Models\Casts\CastFieldPrice;
use IlBronza\Products\Models\AccessoryType;

trait IsAccessorySellableSupplierTrait
{
	public function initializeIsAccessorySellableSupplierTrait() : void
	{
		$prices = AccessoryType::gpc()::make()->getPriceFieldsForSellable();

		$casts = [];

		foreach ($prices as $field => $measurementUnit) {
			$casts[$field] = CastFieldPrice::class . ":{$field},$measurementUnit";
		}

		$this->casts = array_merge($this->casts, $casts);
	}
}