<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Order;

class OrderChildrenFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		$parameters = [
			'translationPrefix' => 'products::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfOrder' => 'products::orders.order',

                'created_at' => [
                    'type' => 'dates.datetime',
                    'order' => [
                        'priority' => 10,
                        'type' => 'DESC'
                    ]
                ],

	            'children' => 'relations.hasMany',
                'mySelfDelete' => 'links.delete'
            ]
        ];


		if(! Order::gpc()::canHaveChildren())
			unset($parameters['fields']['children']);

		return $parameters;
	}
}