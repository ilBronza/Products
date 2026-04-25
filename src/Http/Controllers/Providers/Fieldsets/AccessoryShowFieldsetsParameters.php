<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function config;

class AccessoryShowFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
				'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    'quantity_neeeded_in_stock' => ['number' => 'numeric|nullable|min:0'],
                    'accessory_type_id' => [
                        'readOnly' => true,
                        'type' => 'select',
                        'select2' => false,
                        'multiple' => false,
                        'rules' => 'string|nullable|exists:' . config('products.models.accessoryType.table') . ',id',
                        'relation' => 'accessoryType'
                    ],
	                'parent_id' => [
		                'type' => 'text',
		                'visible' => false,
		                'value' => $this->getModel()?->parent_id,
		                'rules' => 'string|nullable|exists:' . config('products.models.accessory.table') . ',id',
		                'relation' => 'parent'
	                ],
                ],
                'width' => ['1-2@m']
            ],
            'file' => [
	            'translationPrefix' => 'products::fields',
                'fields' => [
                    'images' => [
                        'readOnly' => true,
                        'type' => 'file',
                        'multiple' => true,
                        'rules' =>'string|nullable|max:2048'],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
