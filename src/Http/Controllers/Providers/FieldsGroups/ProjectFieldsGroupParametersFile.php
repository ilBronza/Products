<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class ProjectFieldsGroupParametersFile extends FieldsGroupParametersFile
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
                'client' => 'relations.belongsTo',
                'category' => 'relations.belongsTo',
                'quotations' => 'relations.hasMany',

                'created_at' => 'dates.date',
                'updated_at' => 'dates.date',
                'started_at' => 'dates.date',
                'completed_at' => 'dates.date',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}