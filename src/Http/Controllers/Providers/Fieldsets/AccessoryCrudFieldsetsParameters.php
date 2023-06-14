<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class AccessoryCrudFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    'temp_position' => ['text' => 'string|nullable|max:255'],
                    'quantity_neeeded_in_stock' => ['number' => 'numeric|nullable|min:0']
                ],
                'width' => ['1-2@m']
            ],
            'file' => [
                'fields' => [
                    'images' => [
                        'type' => 'file',
                        'multiple' => true,
                        'rules' =>'string|nullable|max:2048'],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
