<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OrderrowFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'products::fields',
            'fields' =>
            [
                'mySelfPrimary' => 'primary',
	            'sellable' => [
		            'type' => 'links.seeName',
		            'width' => '20rem'
	            ],
	            'order' => 'products::orders.order',
                'order.client' => 'clients::client.client',
	            'order.project' => 'products::projects.project',
	            'quantity' => 'flat',
	            'cost_company' => 'flat',
	            'client_price' => 'flat',
            ]
        ];

	}
}