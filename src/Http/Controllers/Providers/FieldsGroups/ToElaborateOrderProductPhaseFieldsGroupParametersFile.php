<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Order;

class ToElaborateOrderProductPhaseFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',
                // 'order' => 'json',

                // 'order_id' => [
                //     'type' => 'links.LinkCachedProperty',
                //     'modelClass' => Order::getProjectClassName(),
                //     'property' => 'name',
                //     'avoidIcon' => true
                // ],

                // 'sorting_index' => 'flat',
                // 'name' => 'flat',
                // 'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}