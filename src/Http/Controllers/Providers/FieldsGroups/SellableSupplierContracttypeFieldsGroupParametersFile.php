<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

class SellableSupplierContracttypeFieldsGroupParametersFile extends SellableSupplierBaseFieldsGroupParametersFile
{
	static function getTypedFields(string $containerModel) : array
	{
		return [
			'supplier.target.active' => [
				'type' => 'boolean',
				'width' => '2em',
				'defaultWidth' => '2em',
				'valueAsRowClass' => true
			],
			'supplier.target.operator.address.city' => 'flat',
			'supplier.target.operator.address.province' => 'flat',
			'supplier.target.operator.operatorContracttypes' => [
				'translatedName' => 'Mansioni',
				'type' => 'iterators.each',
				'childParameters' => [
					'type' => 'function',
					'function' => 'getContracttypeName'
				],
				'width' => '20em'
			],

			'supplier.target.operator.validClientOperator.employment.label_text' => 'flat',
			'supplier.target.operator.validClientOperator.ended_at' => 'dates.date',

			'cost_company_day' => 'numbers.number2',
			'cost_gross_day' => 'numbers.number2',
			// 'operator_neat_day' => 'numbers.number2',

		];
	}
}