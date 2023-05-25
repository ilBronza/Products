<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class RelatedOrderFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfSee' => 'links.see',
                'code' => 'flat',
                'created_at' => 'dates.datetime',
                'mySelfClass' => 'classname',
                // 'extraFields' => 'json',
                'completed_at' => 'dates.datetime',
                'delivery_sorting' => 'flat'
            ]
        ];
	}
}