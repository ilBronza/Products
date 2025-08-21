<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class ProductShowFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
	            'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    'slug' => [
                        'type' => 'text',
                        'rules' => 'string|nullable|max:255',
                        'disabled' => true
                    ],
	                'short_description' => ['text' => 'string|nullable|max:255'],
	                'coefficient_output' => ['number' => 'numeric|nullable|min:1'],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
