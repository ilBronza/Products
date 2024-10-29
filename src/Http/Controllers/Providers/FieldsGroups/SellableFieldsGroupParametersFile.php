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
                'type' => 'flat',
                'category' => 'relations.belongsTo',
                'target' => 'relations.belongsTo',
                'suppliers_count' => 'flat',
                'quotations_count' => 'flat',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}