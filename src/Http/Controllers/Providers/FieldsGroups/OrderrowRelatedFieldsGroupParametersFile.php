<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class OrderrowRelatedFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
	            'sellable.name' => 'flat',
                'order.client' => 'clients::client.client',
                'order' => 'products::orders.order',
                'order.project' => 'products::projects.project',
                'starts_at' => 'dates.date',
                'ends_at' => 'dates.date'
            ]
        ];
	}
}