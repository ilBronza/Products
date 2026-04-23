<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class CateringProductFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup() : array
	{
		return [
			'translationPrefix' => 'products::fields',
            'fields' =>
            [
				'mySelfPrimary' => 'primary',
				'mySelfThumbnail' => 'media.media',
				'mySelfEdit' => 'links.edit',
				'mySelfSee' => 'links.see',
				'created_at' => 'dates.datetime',
				'name' => 'flat',
				'client_price' => 'flat',
				'descendants' => 'relations.hasMany',
				'categories' => 'relations.belongsToMany',
				'short_description' => 'flat',
				'coefficient_output' => 'editor.numeric',
				'served_at_table' => 'editor.toggle',

	            'single_cost' => 'editor.price',
	            'single_revenue' => 'editor.price',


	            'mySelfDelete' => 'links.delete'
            ]
        ];
	}
}