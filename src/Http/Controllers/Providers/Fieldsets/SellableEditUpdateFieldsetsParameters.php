<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class SellableEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'package' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => ['text' => 'string|required'],
                    'slug' => ['text' => 'string|required'],
                    'category' => [
                        'type' => 'select',
                        'multiple' => false,
                        'rules' => 'string|required|exists:' . config('category.models.category.table') . ',id',
                        'relation' => 'category'
                    ],
                ],
                'width' => ["1-3@l", '1-2@m']
            ]
        ];
    }
}
