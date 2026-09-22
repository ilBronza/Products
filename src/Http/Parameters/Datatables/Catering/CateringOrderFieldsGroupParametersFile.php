<?php

namespace IlBronza\Products\Http\Parameters\Datatables\Catering;


use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Order;

class CateringOrderFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		$parameters = [
			'translationPrefix' => 'products::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfOrder' => [
                    'type' => 'products::orders.order',
                    'translatedName' => trans('products::fields.order')
                ],
                'created_at' => [
                    'type' => 'dates.datetime',
                    'order' => [
                        'priority' => 10,
                        'type' => 'DESC'
                    ]
                ],
                'starts_at' => 'dates.datetime',
                'ends_at' => 'dates.datetime',
	            'parent' => 'relations.belongsTo',
	            'children' => 'relations.hasMany',
                'client' => 'clients::client.client',
                'state_id' => 'flat',
                'mySelfDelete' => 'links.delete'
            ]
        ];


		if(! Order::gpc()::canHaveChildren())
		{
			unset($parameters['fields']['parent']);
			unset($parameters['fields']['children']);
		}

		return $parameters;
	}
}