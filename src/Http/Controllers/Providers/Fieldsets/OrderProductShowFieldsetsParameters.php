<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class OrderProductShowFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    'quantity_required' => ['number' => 'numeric|nullable|min:0|max:65535'],
                    'quantity_done' => ['number' => 'numeric|nullable|min:0|max:65535'],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
