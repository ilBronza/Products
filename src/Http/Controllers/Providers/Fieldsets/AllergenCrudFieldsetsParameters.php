<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class AllergenCrudFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		return [
			'base' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'name' => ['text' => 'string|required|max:64'],
				],
				'width' => ['large'],
			],
		];
	}
}
