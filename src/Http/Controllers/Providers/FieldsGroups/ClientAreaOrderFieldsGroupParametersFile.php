<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ClientAreaOrderFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'created_at' => [
                    'type' => 'dates.datetime',
                    'order' => [
                        'priority' => 10,
                        'type' => 'DESC'
                    ]
                ],
                'name' => 'flat',
            ]
        ];
	}
}