<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class WorkstationCreateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'package' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
	                'name' => ['text' => 'string|required|max:255'],
                ],
                'width' => ["1-3@l", '1-2@m']
            ]
        ];
    }
}
