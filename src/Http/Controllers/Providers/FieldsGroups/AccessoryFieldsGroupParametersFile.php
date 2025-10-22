<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class AccessoryFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',
                'created_at' => 'dates.datetime',
                'name' => 'flat',
	            'mySelfMedia' => 'media.media',
	            'media' => 'json',
	            'parent' => 'relations.belongsTo',
	            'children' => 'relations.hasMany',
                'temp_position' => 'flat',
                'quantity_neeeded_in_stock' => 'flat',
                'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}