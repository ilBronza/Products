<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class VehicleSellableSupplierRelatedFieldsGroupParametersFile extends SellableFieldsGroupParametersFile
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
					'supplier.target' => [
						'type' => 'links.seeName',
						'width' => 'auto',
					],
					'distance_price' => 'numbers.price',
					'target' => 'links.see',
					'suppliers_count' => 'flat',
					'quotations_count' => 'flat',
					'orders_count' => 'flat',

					'mySelfDelete' => 'links.delete'
				]
		];
	}
}