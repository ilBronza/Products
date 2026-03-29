<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class VehicleSellableSupplierBySupplierRelatedFieldsGroupParametersFile extends FieldsGroupParametersFile
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
					'sellable' => 'products::sellables.sellable',
					'mySelfPrices' => 'products::sellableSuppliers.pricesList',
					'quotations_count' => 'flat',
					'orders_count' => 'flat',

					'mySelfDelete' => 'links.delete'
				]
		];
	}
}