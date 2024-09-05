<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class QuotationrowEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'package' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'name' => ['text' => 'string|required'],
	                'slug' => ['text' => 'string|required'],

	                'half_start' => ['boolean' => 'bool|nullable'],

	                'starts_at' => ['date' => 'date|nullable'],
	                'ends_at' => ['date' => 'date|nullable'],
	                'half_end' => ['boolean' => 'bool|nullable'],

	                'calculated_cost_company' => ['number' => 'numeric|nullable'],
	                'calculated_client_price' => ['number' => 'numeric|nullable'],

	                'calculated_cost_company_total' => ['number' => 'numeric|nullable'],
	                'calculated_client_price_total' => ['number' => 'numeric|nullable'],


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