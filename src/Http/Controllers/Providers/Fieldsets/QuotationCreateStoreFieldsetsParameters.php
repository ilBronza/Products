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
                    'client' => [
                        'type' => 'select',
                        'multiple' => false,
                        'rules' => 'string|nullable|exists:' . config('clients.models.client.table') . ',id',
                        'relation' => 'client'
                    ],
                ],
                'width' => ["1-3@l", '1-2@m']
            ],
            'dates' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
                    'starts_at' => ['datetime' => 'date|nullable'],
                    'ends_at' => ['datetime' => 'date|nullable'],
                ],
                'width' => ["1-3@l", '1-2@m']
            ],
        ];
    }
}
