<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

class SellableSupplierSurveillanceFieldsGroupParametersFile extends SellableSupplierBaseFieldsGroupParametersFile
{
	static function getTypedFields(string $containerModel) : array
	{
		return [
			//			'supplier.target->defaultDestination->address' => '_fn_getTargetString',

			'id' => 'flat',

			'supplier.target.defaultDestination.address.street_string' => 'flat',

			'supplier.target.defaultDestination.address.zip' => 'flat',

			'supplier.target.defaultDestination.address.city' => 'flat',
			'supplier.target.defaultDestination.address.province' => 'flat',
			'supplier.target.defaultDestination.address.state' => 'flat',

			'cost_company_day' => 'flat',

			//			'supplier.target.address.city' => 'flat',
			//			'supplier.target.address.province' => 'flat',
			//			'id' => 'flat',
			//			'supplier.target.plate' => 'flat',
		];
	}
}