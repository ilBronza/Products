<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use IlBronza\Products\Models\Order;

use function config;

class OrderCreateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        $parameters = [
            'base' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => ['text' => 'string|required'],
                    'client' => [
                        'type' => 'select',
                        'multiple' => false,
                        'rules' => 'string|required|exists:' . config('clients.models.client.table') . ',id',
                        'relation' => 'client'
                    ],
	                'parent_id' => [
		                'type' => 'select',
		                'multiple' => false,
		                'rules' => 'string|nullable|exists:' . config('products.models.order.table') . ',id',
		                'relation' => 'parent'
	                ]
                ],
                'width' => ["1-3@l", '1-2@m']
            ]
        ];

		if(! Order::gpc()::canHaveChildren())
			unset($parameters['base']['fields']['parent_id']);

		return $parameters;
    }
}
