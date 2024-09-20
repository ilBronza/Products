<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

class SellableSupplierVehicletypeFieldsGroupParametersFile extends SellableSupplierBaseFieldsGroupParametersFile
{
	static function getTypedFields() : array
	{
		return [
			'supplier.target.plate' => 'flat',
			'distance_price' => 'flat',
		];
	}
}