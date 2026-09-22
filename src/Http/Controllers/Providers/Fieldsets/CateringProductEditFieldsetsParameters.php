<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Products\Models\Accessory;
use IlBronza\Products\Models\AccessoryType;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Providers\Helpers\RowsHelpers\CostsFieldsetParametersFile;

class CateringProductEditFieldsetsParameters extends CostsFieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        $costFields = static::getCostsFieldsetByModel(
            Product::gpc()::make()
        );

        return [
            'base' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => [
                        'text' => 'string|nullable|max:255'
                    ],
                    'slug' => [
                        'type' => 'text',
                        'rules' => 'string|nullable|max:255',
                        'disabled' => true
                    ],
                    'short_description' => ['text' => 'string|nullable|max:255'],
                    'coefficient_output' => ['number' => 'numeric|nullable|min:1'],
					'allergens' => [
						'type' => 'select',
						'multiple' => true,
						'mustBeSorted' => false,
						'rules' => [
							'nullable',
							'array',
							'*' => 'exists:' . config('products.models.allergen.table') . ',id',
						],
						'relation' => 'allergens',
					],
					'accessories' => [
						'type' => 'select',
						'multiple' => true,
						'relation' => 'accessories',
						'rules' => 'array|nullable',
					],
					'accessoryTypes' => [
						'type' => 'select',
						'multiple' => true,
						'relation' => 'accessoryTypes',
						'rules' => 'array|nullable',
					],
                    'image' => [
                        'type' => 'file',
                        'persist' => false,
                        'collection' => 'default',
                        'multiple' => false,
                        'rules' => 'file|nullable|max:255'
                    ],
                ],
                'width' => ['1-2@m']
            ],
            'cost' => [
                'translationPrefix' => 'products::fields',
                'fields' => $costFields,
                'width' => ['1-2@m']
            ]
        ];
    }
}
