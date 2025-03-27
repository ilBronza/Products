<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class SellableSupplierByOperatorFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'products::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',

				'sellable' => 'links.seeName',

				'cost_company_day' => 'editor.price',
				'cost_gross_day' => 'editor.price',
				'operator_neat_day' => 'editor.price',

				'mySelfDelete' => 'links.delete'

			]
		];
	}
}