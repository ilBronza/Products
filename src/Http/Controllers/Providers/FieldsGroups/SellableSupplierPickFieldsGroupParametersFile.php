<?php

namespace IlBronza\Products\Http\Controllers\Providers\FieldsGroups;

use IlBronza\Clients\Models\Client;
use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use Illuminate\Database\Eloquent\Model;

class SellableSupplierPickFieldsGroupParametersFile extends FieldsGroupParametersFile
{
    static function getFieldsGroup(Model $model = null) : array
    {
        return [
            'translationPrefix' => 'products::fields',
            'fields' => 
            [
                'mySelfPrimary' => 'primary',
                // 'mySelfEdit' => 'links.edit',
                // 'mySelfSee' => 'links.see',
				'supplier.target.name' => 'flat',
				'mySelfAssign' => "products::{$model->getModelConfigPrefix()}rows.addSellableSupplierRow",'sellable.name' => 'flat',
	            'sellable.type' => 'flat',

				'prices' => [
					'type' => 'iterators.each',
					'childParameters' => [
						'type' => 'function',
						'function' => 'getPriceDescriptionString'
					],
				],

	            // 'quotationrows_count' => 'flat',
	            // 'orderrows_count' => 'flat',

                // 'mySelfDelete' => 'links.delete'
            ]
        ];
    }
}