<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class QuotationCreateStoreFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'package' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => ['text' => 'string|required'],
                    'slug' => ['text' => 'string|required'],
                    // 'client' => [
                    //     'type' => 'select',
                    //     'multiple' => false,
                    //     'rules' => 'string|required|exists:' . config('clients.models.client.table') . ',id',
                    //     'relation' => 'client'
                    // ],
                ],
                'width' => ["1-3@l", '1-2@m']
            ],
            // 'status' => [
            //     'translationPrefix' => 'products::fields',
            //     'fields' => [
            //         'category' => [
            //             'type' => 'select',
            //             'multiple' => false,
            //             'rules' => 'string|required|exists:' . config('category.models.category.table') . ',id',
            //             'relation' => 'category'
            //         ],
            //         'started_at' => ['datetime' => 'date|nullable'],
            //         'completed_at' => ['datetime' => 'date|nullable'],
            //     ],
            //     'width' => ["1-3@l", '1-2@m']
            // ]
        ];
    }
}
