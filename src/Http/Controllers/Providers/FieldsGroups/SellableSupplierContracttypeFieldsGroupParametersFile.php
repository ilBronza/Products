<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

class SellableSupplierContracttypeFieldsGroupParametersFile extends SellableSupplierBaseFieldsGroupParametersFile
{
	static function getTypedFields(string $containerModel) : array
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

			'supplier.target.validClientOperator.employment.label_text' => 'flat',
			'supplier.target.validClientOperator.ended_at' => 'dates.date',

			'cost_company_day' => 'numbers.number2',
			'cost_gross_day' => 'numbers.number2',
			// 'operator_neat_day' => 'numbers.number2',

		];
	}
}