<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Product\Product;

class ByProductRelatedProductRelationFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'products::fields',
            'fields' =>
            [
                'mySelfPrimary' => 'primary',
                'mySelfSee' => 'links.see',
                'mySelfEdit' => 'links.edit',
                'created_at' => 'dates.datetime',
                'child' => 'products::products.product',
                'mySelfChildrenPhases.child_id' => [
                    'type' => 'models.CachedModelProperty',
                    'modelClass' => Product::getProjectClassName(),
                    'property' => 'phases_description_string'
                ],
                'quantity_coefficient' => 'flat',
                'measurement_unit_id' => 'measurementUnits::measurementUnit.measurementUnit',
            ]
        ];
	}
}