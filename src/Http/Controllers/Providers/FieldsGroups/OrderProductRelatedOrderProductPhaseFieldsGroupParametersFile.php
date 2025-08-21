<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use App\Models\ProductsPackage\Product;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Order;

class OrderProductRelatedOrderProductPhaseFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',

                'live_order_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Order::class,
                    'function' => 'getOldShowOrderUrl',
                    'property' => 'name',
                    'avoidIcon' => true
                ],

                // 'live_order_id' => [
                //     'type' => 'links.LinkCachedProperty',
                //     'modelClass' => Order::getProjectClassName(),
                //     'property' => 'name',
                //     'avoidIcon' => true
                // ],

                'live_product_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Product::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true
                ],

                'sorting_index' => 'flat',

                'quantity_required' => 'flat',
                'quantity_done' => 'flat',
                'calculated_workstation_id' => 'flat',
                'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}