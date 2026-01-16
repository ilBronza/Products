<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class SellableFieldsGroupParametersFile extends FieldsGroupParametersFile
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
	            'name' => 'flat',
	            'slug' => 'flat',
	            'type' => 'flat',
	            'prices' => 'prices::pricesList',
	            'category' => 'relations.belongsTo',
	            'target' => 'links.see',
	            'suppliers_count' => 'flat',
	            'quotations_count' => 'flat',
	            'orders_count' => 'flat',

	            'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}