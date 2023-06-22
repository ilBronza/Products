<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Product\Product;

class ByProductRelatedProductRelationFieldsGroupParametersFile extends FieldsGroupParametersFile
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
                'child_id' => [
                    'type' => 'links.LinkCachedProperty',
                    'modelClass' => Product::getProjectClassName(),
                    'property' => 'name',
                    'avoidIcon' => true
                ],
                'mySelfChildrenPhases.child_id' => [
                    'type' => 'models.CachedModelProperty',
                    'modelClass' => Product::getProjectClassName(),
                    'property' => 'phases_description_string'
                ],
                'mySelfChildrenCount.child_id' => [
                    'type' => 'models.CachedModelProperty',
                    'modelClass' => Product::getProjectClassName(),
                    'property' => 'children_count'
                ],
                'quantity_coefficient' => 'flat',
                'mySelfPricePiece.child_id' => [
                    'type' => 'models.CachedModelProperty',
                    'modelClass' => Product::getProjectClassName(),
                    'property' => 'price'
                ],
            ]
        ];
	}
}