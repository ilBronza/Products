<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use App\Models\Client\Client;
use IlBronza\Clients\Models\Destination;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Product\Product;

class ByWorkstationRelatedOrderProductFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfSee' => 'links.see',
                'created_at' => 'dates.datetime',
                'live_client_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Client::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true
                ],
                'order_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Order::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true
                ],
                'product_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Product::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true
                ],
                'live_wave_alias' => 'flat',
                'live_supplier_id' => 'flat',
                'live_product_sizes' => 'flat',
                'live_product_sizes' => 'flat',
                'live_completed_at' => 'flat',
                'quantity_required' => 'flat',
                // 'mySelfEstimated_time' => '_fn_getPublicHumanReadableEstimatedTime',
                'quantity_done' => 'flat',
            ]
        ];
	}
}