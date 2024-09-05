<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class SellableSupplierFieldsGroupParametersFile extends FieldsGroupParametersFile
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
				'id' => 'flat',
				'supplier.target.name' => 'flat',
				'sellable.name' => 'flat',

				'prices' => [
					'type' => 'iterators.each',
					'childParameters' => [
						'type' => 'function',
						'function' => 'getPriceDescriptionString'
					],
				],

				'quotationrows_count' => 'flat',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}