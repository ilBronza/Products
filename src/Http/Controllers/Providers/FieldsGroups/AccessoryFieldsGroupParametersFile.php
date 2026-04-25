<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\AccessoryType;
use IlBronza\Products\Providers\Helpers\RowsHelpers\CostsFieldsGroupParametersFile;

class AccessoryFieldsGroupParametersFile extends CostsFieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
        return [
            'translationPrefix' => 'products::fields',
            'fields' => static::addStandardCostsFieldsByModelPlusDelete(
                [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
                'mySelfSee' => 'links.see',
                'name' => 'flat',
                'accessoryType' => 'relations.belongsTo',
                'mySelfMedia' => 'media.media',
                'media' => 'json',
                'parent' => 'relations.belongsTo',
                'children' => 'relations.hasMany',
                'temp_position' => 'flat',
                'quantity_neeeded_in_stock' => 'flat',
                ],
                AccessoryType::gpc()::make()
            )
        ];
	}
}