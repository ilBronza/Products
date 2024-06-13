<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class QuotationFieldsGroupParametersFile extends FieldsGroupParametersFile
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
                'date' => 'dates.datetime',
                'project' => 'relations.belongsTo',
                'client' => 'relations.belongsTo',
                'destination' => 'relations.belongsTo',
                'parent' => 'relations.belongsTo',
                // 'category' => 'relations.belongsTo',
                'quotationrows' => 'relations.hasMany',
                'price.own_cost' => 'flat',
                'price.price' => 'flat',

                // 'created_at' => 'dates.date',
                // 'updated_at' => 'dates.date',
                // 'started_at' => 'dates.date',
                // 'completed_at' => 'dates.date',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}