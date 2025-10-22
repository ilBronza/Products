<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function array_keys;
use function implode;

class SellableCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		$possibleTypesValues = $this->getModel()->getPossibleTypeValuesArray();

		return [
			'base' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'name' => ['text' => 'string|required'],
					'slug' => ['text' => 'string|nullable'],
					'type' => [
						'type' => 'select',
						'select2' => false,
						'list' => $possibleTypesValues,
						'rules' => 'string|required|in:' . implode(",", array_keys($possibleTypesValues))
					],
//					'category' => [
//						'type' => 'select',
//						'multiple' => false,
//						'rules' => 'string|required|exists:' . config('category.models.category.table') . ',id',
//						'relation' => 'category'
//					],
				],
				'width' => ["1-3@l", '1-2@m']
			]
		];
	}
}
