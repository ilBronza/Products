<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

class SellableSupplierVehicletypeFieldsGroupParametersFile extends SellableSupplierBaseFieldsGroupParametersFile
{
	static function getTypedFields(string $containerModel) : array
	{
		return [
			'supplier.target.plate' => 'flat',
			'supplier.target.slug' => 'flat',
			'distance_price' => 'flat',
		];
	}
}