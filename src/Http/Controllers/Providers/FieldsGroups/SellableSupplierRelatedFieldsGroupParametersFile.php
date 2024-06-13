<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class SellableSupplierRelatedFieldsGroupParametersFile extends FieldsGroupParametersFile
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
                'supplier.target.name' => 'flat',
                'directPrice.price' => [
                    'type' => 'flat',
                    'suffix' => '€'
                ],
                'directPrice.measurement_unit_id' => 'flat',

                // 'category' => 'relations.belongsTo',
                // 'target' => 'relations.belongsTo',
                // 'sellableSuppliers' => [
                //     'type' => 'relations.hasMany'
                // ],
                // 'suppliers' => 'relations.belongsToMany',
                // 'quotations_count' => 'flat',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}