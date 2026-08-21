<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Products\Providers\Helpers\Permissions\EconomicsPermissionsHelper;

class SellableSupplierContracttypeFieldsGroupParametersFile extends SellableSupplierBaseFieldsGroupParametersFile
{
	static function getFieldsGroupByContainerModel(string $containerModel) : array
	{
		return EconomicsPermissionsHelper::mergeInto(
			parent::getFieldsGroupByContainerModel($containerModel),
			[
				'cost_company_day',
				'cost_gross_day',
			]
		);
	}

	static function getTypedFields(string $containerModel) : array
	{
		return [
			'supplier.target.operator.active' => [
				'type' => 'boolean',
				'width' => '3em',
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

			'cost_gross_day' => 'numbers.number2',
			'cost_company_day' => 'numbers.number2',

		];
	}
}