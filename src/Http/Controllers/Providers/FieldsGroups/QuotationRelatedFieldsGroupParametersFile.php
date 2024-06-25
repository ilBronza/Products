<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class QuotationRelatedFieldsGroupParametersFile extends FieldsGroupParametersFile
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
                'quotationrows_count' => 'flat',
                'project' => 'relations.belongsTo',
                'client' => 'relations.belongsTo',
                // 'destination' => 'relations.belongsTo',
                // 'parent' => 'relations.belongsTo',
                // 'category' => 'relations.belongsTo',
                // 'quotationrows' => 'relations.hasMany',
                // 'price.own_cost' => 'flat',
                'directPrice.price' => 'flat',

                // 'created_at' => 'dates.date',
                // 'updated_at' => 'dates.date',
                // 'started_at' => 'dates.date',
                // 'completed_at' => 'dates.date',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}