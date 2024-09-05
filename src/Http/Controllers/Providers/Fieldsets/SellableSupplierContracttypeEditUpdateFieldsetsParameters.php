<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class SellableSupplierContracttypeEditUpdateFieldsetsParameters extends SellableSupplierBaseEditUpdateFieldsetsParameters
{
	static function getTypedFieldsets() : array
	{
		return [
			'prices' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'cost_company_day' => ['number' => 'numeric|required'],
					'cost_gross_day' => ['number' => 'numeric|nullable'],
					'operator_neat_day' => ['number' => 'numeric|nullable'],
				],
				'width' => ['large']
			]
		];
	}


}
