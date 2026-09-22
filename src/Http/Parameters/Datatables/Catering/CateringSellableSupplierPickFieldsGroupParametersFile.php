<?php

namespace IlBronza\Products\Http\Parameters\Datatables\Catering;

use Illuminate\Database\Eloquent\Model;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierPickFieldsGroupParametersFile;

/**
 * Catering-specific fields configuration for selecting product suppliers.
 */
class CateringSellableSupplierPickFieldsGroupParametersFile extends SellableSupplierPickFieldsGroupParametersFile
{
    static function getFieldsGroup(Model $model = null) : array
    {
        return [
            'translationPrefix' => 'products::fields',
            'fields' => 
            [
				'mySelfPrimary' => 'primary',
				'sellable.target' => 'media.media',
				'mySelfAssign' => "products::{$model->getModelConfigPrefix()}rows.addSellableSupplierRow",'sellable.name' => 'flat',
				'sellable.target.allergens_list' => 'products::catering.allergens',

				'sellable.target.descendants' => [
					'type' => 'iterators.each',
					'childParameters' => [
						'type' => 'flat',
						'property' => 'name',
					]
				],

				'sellable.target.categories' => [
					'type' => 'iterators.each',
					'childParameters' => [
						'type' => 'flat',
						'property' => 'name',
					]
				],

				'prices' => [
					'type' => 'iterators.each',
					'childParameters' => [
						'type' => 'function',
						'function' => 'getPriceDescriptionString'
					],
				],
            ]
        ];
    }
}
