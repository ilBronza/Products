<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ProductFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'products::fields',
            'fields' =>
            [
                'mySelfPrimary' => 'primary',
	            'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',
                'created_at' => 'dates.datetime',
                'name' => 'flat',
                'client_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Client::getProjectClassName(),
                    'property' => 'name'
                ],
	            'product_relations_count' => 'flat',
	            'media_count' => 'flat',
	            'orders_count' => 'flat',
	            'active_orders_count' => 'flat',
	            'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}