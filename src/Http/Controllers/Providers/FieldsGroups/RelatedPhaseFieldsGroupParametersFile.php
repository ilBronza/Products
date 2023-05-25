<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class RelatedPhaseFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                // 'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',
                'created_at' => 'dates.datetime',
                'sorting_index' => 'flat',
                'name' => 'flat',
                // 'client_id' => [
                //     'type' => 'links.LinkCachedProperty',
                //     'modelClass' => Client::getProjectClassName(),
                //     'property' => 'name'
                // ],
                'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}