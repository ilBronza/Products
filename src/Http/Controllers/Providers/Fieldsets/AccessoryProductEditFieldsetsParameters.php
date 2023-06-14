<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class AccessoryProductEditFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
                'fields' => [
                    'quantity_coefficient' => ['number' => 'integer|required|max:65535|min:0'],
                    'phase' => [
                        'type' => 'select',
                        'multiple' => false,
                        'rules' => 'string|nullable|exists:products__phases,id',
                        'relation' => 'phase'
                    ],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
