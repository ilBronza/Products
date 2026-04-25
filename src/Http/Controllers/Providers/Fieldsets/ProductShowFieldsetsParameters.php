<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Products\Models\Accessory;
use IlBronza\Products\Models\AccessoryType;

class ProductShowFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
		$product = $this->getModel();

		$accessoriesList = $product
			? Accessory::getPossibleAccessoriesSelectListByProduct($product)
			: Accessory::gpc()::query()->pluck('name', 'id');

		$accessoryTypesList = AccessoryType::gpc()::query()->pluck('name', 'id');

        return [
            'base' => [
	            'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    'slug' => [
                        'type' => 'text',
                        'rules' => 'string|nullable|max:255',
                        'disabled' => true
                    ],
	                'short_description' => ['text' => 'string|nullable|max:255'],
	                'coefficient_output' => ['number' => 'numeric|nullable|min:1'],
					'accessories' => [
						'type' => 'select',
						'multiple' => true,
						'list' => $accessoriesList,
						'relation' => 'accessories',
						'rules' => 'string|nullable',
					],
					'accessoryTypes' => [
						'type' => 'select',
						'multiple' => true,
						'list' => $accessoryTypesList,
						'relation' => 'accessoryTypes',
						'rules' => 'string|nullable',
					],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
