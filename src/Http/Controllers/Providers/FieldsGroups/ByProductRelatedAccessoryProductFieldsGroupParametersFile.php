<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Accessory;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\Product;

class ByProductRelatedAccessoryProductFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfSee' => 'links.see',
                'mySelfEdit' => [
                    'type' => 'links.edit',
                    'icon' => 'cog'
                ],
                'created_at' => 'dates.datetime',
                'accessory_id' => [
                    'type' => 'models.CachedModelProperty',
                    'modelClass' => Accessory::getProjectClassName(),
                    'property' => 'name'
                ],
                'phase_id' => [
                    'type' => 'models.CachedModelProperty',
                    'modelClass' => Phase::getProjectClassName(),
                    'property' => 'name'
                ],                
                'quantity_coefficient' => 'flat',
            ]
        ];
	}
}