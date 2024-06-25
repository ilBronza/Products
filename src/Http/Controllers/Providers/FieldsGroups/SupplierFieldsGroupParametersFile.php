<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class SupplierFieldsGroupParametersFile extends FieldsGroupParametersFile
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
                'target.name' => 'flat',
                'target.destinations' => [
                    'type' => 'iterators.each',
                    'childParameters' => [
                        'type' => 'flat',
                        'property' => 'province'
                    ],
                ],
                'mySelfFullAddress.target.destinations' => [
                    'type' => 'iterators.each',
                    'childParameters' => [
                        'type' => 'function',
                        'function' => 'getFlatDescriptionString'
                    ],
                    'width' => '450px'
                ],
                'quotationrows_count' => 'flat',
                // 'target' => 'json',
                'categories' => 'relations.belongsToMany',
                'sellables' => 'relations.belongsToMany',
                'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}