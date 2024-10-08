<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

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
	            'created_at' => 'dates.datetime',
                'name' => 'flat',
	            'client' => 'relations.belongsTo',
	            'project' => 'relations.belongsTo',
	            'destination' => 'relations.belongsTo',
                'slug' => 'flat',
                'date' => 'dates.datetime',
//                'parent' => 'relations.belongsTo',
                // 'category' => 'relations.belongsTo',
                'quotationrows' => 'relations.hasMany',
//                'price.own_cost' => 'flat',
//                'price.price' => 'flat',

                // 'created_at' => 'dates.date',
                // 'updated_at' => 'dates.date',
                // 'started_at' => 'dates.date',
                // 'completed_at' => 'dates.date',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}