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
                'translatedLegend' => 'IPOTETICI OCCHIO ZIO CULO',
                'fields' => [
                    'cost_company' => ['number' => 'numeric|required'],
                    'cost_client' => ['number' => 'numeric|required'],

                    // 'name' => ['text' => 'string|required'],
                    // 'slug' => ['text' => 'string|required'],
                    // 'target' => [
                    //     'type' => 'select',
                    //     'multiple' => false,
                    //     'rules' => 'string|nullable',
                    //     'relation' => 'target'
                    // ],
                    // 'category' => [
                    //     'type' => 'select',
                    //     'multiple' => false,
                    //     'rules' => 'string|required|exists:' . config('category.models.category.table') . ',id',
                    //     'relation' => 'category'
                    // ],
                ],
                'width' => ["1-3@l", '1-2@m']
            ]
        ];
    }
}
