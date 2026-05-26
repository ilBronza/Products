<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class PickOrderrowSupplierFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup($containerModel) : array
	{
		return [
            'translationPrefix' => 'products::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
				'target.name' => 'flat',
				'mySelfPick' => "products::{$containerModel->getModelConfigPrefix()}rows.addBySupplier",
				'mySelfTargetClass.target' => 'models.classBasename',
            ]
        ];
	}
}