<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class SellableSupplierCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'package' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'supplier_id' => [
                        'type' => 'select',
                        'multiple' => false,
                        'rules' => 'string|required',//|in:' . config('products.models.supplier.table') . ',id',
                        'relation' => 'supplier'
                    ],
                    'sellable_id' => [
                        'type' => 'select',
                        'multiple' => false,
                        'rules' => 'string|required',//|in:' . config('products.models.sellable.table') . ',id',
                        'relation' => 'sellable'
                    ]
                ],
                'width' => ["1-3@l", '1-2@m']
            ]
        ];
    }
}
