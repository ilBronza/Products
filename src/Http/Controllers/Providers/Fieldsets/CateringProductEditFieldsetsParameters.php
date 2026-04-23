<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

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
