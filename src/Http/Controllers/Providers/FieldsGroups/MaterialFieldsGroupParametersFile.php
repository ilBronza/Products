<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class MaterialFieldsGroupParametersFile extends FieldsGroupParametersFile
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
	            'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}