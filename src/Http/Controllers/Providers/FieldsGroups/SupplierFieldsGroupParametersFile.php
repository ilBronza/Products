<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class SupplierFieldsGroupParametersFile extends FieldsGroupParametersFile
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
                'target.name' => 'flat',
                // 'target' => 'json',
                'categories' => 'relations.belongsToMany',
                'sellables' => 'relations.belongsToMany',
                'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}