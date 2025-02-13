<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class SellableSupplierHotelEditUpdateFieldsetsParameters extends SellableSupplierBaseEditUpdateFieldsetsParameters
{
	static function getTypedFieldsets() : array
	{
		return [
			'prices' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'cost_company_day' => ['number' => 'numeric|required'],
				],
				'width' => ['large']
			]
		];
	}


}
