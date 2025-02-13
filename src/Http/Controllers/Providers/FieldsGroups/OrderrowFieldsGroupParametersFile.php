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
	            'order' => [
					'type' => 'links.seeName',
		            'width' => '9rem'
	            ],
	            'order.client' => [
		            'type' => 'links.seeName',
		            'width' => '15rem'
	            ],
	            'order.project' => [
		            'type' => 'links.seeName',
		            'width' => '20rem'
	            ],
	            'quantity' => 'flat',
	            'cost_company' => 'flat',
	            'client_price' => 'flat',
            ]
        ];

	}
}