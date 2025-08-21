<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Order;

class RelatedOrderProductPhaseFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                // 'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',
                'order_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Order::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true
                ],
                'sorting_index' => 'flat',
                'coefficient_output' => 'flat',
                'quantity_required' => 'flat',
                'quantity_done' => 'flat',
                'workstation_overridden_id' => 'flat',
                'started_at' => 'dates.datetime',
                'completed_at' => 'dates.datetime',
                'name' => 'flat',
                // 'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}