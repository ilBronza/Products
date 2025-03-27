<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

class SellableSupplierHotelFieldsGroupParametersFile extends SellableSupplierBaseFieldsGroupParametersFile
{
	static function getTypedFields(string $containerModel) : array
	{
		return [
			'mySelfAssignBulk' => "products::{$containerModel}rows.assignBulkSellableSupplier",
			'supplier.target.address.street_string' => 'flat',
			'supplier.target.address.city' => 'flat',
			'supplier.target.address.province' => [
				'type' => 'flat',
				'width' => '3em'
			],
			'supplier.target.address.state' => [
				'type' => 'flat',
				'width' => '3em'
			],

			'mySelfPhone.supplier.target.contacts' => [
				'type' => 'contacts::single',
				'contacttype' => 'backoffice'
			],

			'cost_company_day' => [
				'type' => 'numbers.price',
				//				'width' => '5em'
			],
		];
	}
}