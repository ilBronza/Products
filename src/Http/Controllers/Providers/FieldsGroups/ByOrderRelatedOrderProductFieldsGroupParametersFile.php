<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Destination;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Product;

class ByOrderRelatedOrderProductFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfSee' => 'links.see',
                'created_at' => 'dates.datetime',
                'order_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Order::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true
                ],
                'destination_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Destination::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true
                ],
                'product_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Product::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true
                ],
                'quantity_required' => 'flat',
                'quantity_done' => 'flat',
            ]
        ];
	}
}