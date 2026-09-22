<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class AllergenFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'products::fields',
			'fields' => [
				'mySelfPrimary' => 'primary',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',
				'name' => 'flat',
				'products_in_order_count' => 'flat',
				'mySelfDelete' => 'links.delete',
			],
		];
	}
}
