<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;

class SellableSupplierBySupplierFieldsGroupParametersFile extends FieldsGroupParametersFile
{
    static function getFieldsGroup() : array
    {
        return [
            'translationPrefix' => 'products::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                'mySelfEdit' => 'links.edit',
				'sellable.name' => 'flat',

                'cost_company_day' => 'numbers.price',

				// 'prices' => [
				// 	'type' => 'iterators.each',
				// 	'childParameters' => [
				// 		'type' => 'function',
				// 		'function' => 'getPriceDescriptionString'
				// 	],
				// ],

                'orderrows_count' => 'flat',
                'quotationrows_count' => 'flat',

                'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}