<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class PhaseEditFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    'workstation_id' => [
                        'type' => 'select',
                        'multiple' => false,
                        'rules' => 'integer|nullable|exists:workstations,alias',
                        'relation' => 'workstation',
                        'roles' => [
                            'administrator'
                        ]
                    ],
                    'sorting_index' => ['number' => 'numeric|nullable'],
                    'coefficient_output' => ['number' => 'numeric|nullable'],
                    'slug' => ['text' => 'string|nullable|max:255'],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}



