<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Providers\Helpers\RowsHelpers\CostsFieldsGroupParametersFile;

class SellableSupplierBySupplierFieldsGroupParametersFile extends CostsFieldsGroupParametersFile
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

    static function getFieldsGroupBySellablePrices(SellableItemInterface $sllablePlaceholder) : array
    {
        return [
            'translationPrefix' => 'products::fields',
            'fields' => static::addStandardCostsFieldsByModelPlusDelete(
                [
                    'mySelfPrimary' => 'primary',
                    'mySelfEdit' => 'links.edit',
                    'mySelfSee' => 'links.see',
                    'sellable' => 'products::sellables.sellable',
                    'quotations_count' => 'flat',
                    'orders_count' => 'flat',
                ],
                $sllablePlaceholder
            )
        ];


    }
}