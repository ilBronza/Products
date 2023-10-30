<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class OrderShowFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    'client' => [
                        'type' => 'select',
                        'multiple' => false,
                        'disabled' => true,
                        'rules' => 'string|required|exists:' . config('clients.models.client.table') . ',id',
                        'relation' => 'client'
                    ],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
