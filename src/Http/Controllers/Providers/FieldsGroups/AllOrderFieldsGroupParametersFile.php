<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class AllOrderFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                // 'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',
                'name' => 'flat',
                'client_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Client::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true,
                    'width' => '144px',
                ],
            ]
        ];
	}
}