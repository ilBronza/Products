<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

class SellableSupplierContracttypeFieldsGroupParametersFile extends SellableSupplierBaseFieldsGroupParametersFile
{
	static function getTypedFields() : array
	{
		return [
			'supplier.target.address.city' => 'flat',
			'supplier.target.address.province' => 'flat',
			'supplier.target.operatorContracttypes' => [
				'type' => 'iterators.each',
				'childParameters' => [
					'type' => 'function',
					'function' => 'getContracttypeName'
				],
				'width' => '450px'
			],

			'cost_company_day' => 'flat',
			'cost_gross_day' => 'flat',
			'operator_neat_day' => 'flat',

		];
	}
}