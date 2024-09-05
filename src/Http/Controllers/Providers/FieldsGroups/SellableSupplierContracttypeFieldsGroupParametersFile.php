<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

class SellableSupplierContracttypeFieldsGroupParametersFile extends SellableSupplierBaseFieldsGroupParametersFile
{
	static function getTypedFields() : array
	{
		return [
			'mySelfClass' => 'models.classBasename',
			'id' => 'flat',
			'supplier.target.operatorContracttypes' => [
				'type' => 'iterators.each',
				'childParameters' => [
					'type' => 'function',
					'function' => 'getContracttypeName'
				],
				'width' => '450px'
			],

			'mySelfAssign' => 'products::quotationrows.assignSellableSupplier',

			'cost_company_day' => 'flat',
			'cost_gross_day' => 'flat',
			'operator_neat_day' => 'flat',

		];
	}
}