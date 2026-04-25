<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Products\Models\AccessoryType;
use function config;

class AccessoryTypeCrudFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        $result = [
            'base' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    'sorting_index' => ['number' => 'numeric|nullable|min:0'],
                    'parent_id' => [
                        'type' => 'select',
                        'rules' => 'string|nullable|exists:' . config('products.models.accessoryType.table') . ',id',
                        'relation' => 'parent'
                    ],
                ],
                'width' => ['1-2@m']
            ]
        ];

        return static::addCostsFieldsetByModel(
            $result,
            AccessoryType::gpc()::make(),
            [
                'translationPrefix' => 'products::fields'
            ]
        );

    }
}

