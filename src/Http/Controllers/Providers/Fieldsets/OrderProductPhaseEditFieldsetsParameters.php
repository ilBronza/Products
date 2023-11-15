<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class OrderProductPhaseEditFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
                'fields' => [
                    // 'name' => ['text' => 'string|nullable|max:255'],
                    'workstation_overridden_id' => ['text' => 'string|nullable|exists:workstations,alias'],
                    // 'phase_id' => [
                    //     'type' => 'select',
                    //     'multiple' => false,
                    //     'rules' => 'string|nullable|exists:products__phases,id',
                    //     'relation' => 'phase'
                    // ],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
