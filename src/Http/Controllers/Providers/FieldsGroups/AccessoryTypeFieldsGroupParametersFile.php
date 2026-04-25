<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Products\Models\AccessoryType;
use IlBronza\Products\Providers\Helpers\RowsHelpers\CostsFieldsGroupParametersFile;

class AccessoryTypeFieldsGroupParametersFile extends CostsFieldsGroupParametersFile
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
					'created_at' => 'dates.datetime',
					'name' => 'flat',
					'sorting_index' => 'flat',
				],
				AccessoryType::gpc()::make()
			)
		];
	}
}

