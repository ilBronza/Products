<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierBySupplierFieldsGroupParametersFile;
use IlBronza\Products\Models\AccessoryType;

class AccessorySellableSupplierBySupplierRelatedFieldsGroupParametersFile extends SellableSupplierBySupplierFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return static::getFieldsGroupBySellablePrices(
			AccessoryType::gpc()::make()
		);
	}
}