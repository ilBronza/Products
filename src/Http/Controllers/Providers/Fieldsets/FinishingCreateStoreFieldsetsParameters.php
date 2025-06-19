<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class FinishingCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    'short_description' => ['text' => 'string|nullable|max:255'],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
