<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class ClientAreaOrderProductEditFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
                'fields' => [
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
