<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class SupplierShowFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
                'fields' => [
                    'name' => ['text' => 'string|nullable|max:255'],
                    // 'sequence' => ['number' => 'integer|nullable'],
                    // 'order_product_id' => [
                    //     'type' => 'select',
                    //     'multiple' => false,
                    //     'rules' => 'string|nullable|exists:products__order_products,id',
                    //     'relation' => 'orderProduct'
                    // ],
                    // 'order_id' => [
                    //     'type' => 'select',
                    //     'multiple' => false,
                    //     'rules' => 'string|nullable|exists:products__orders,id',
                    //     'relation' => 'order'
                    // ],
                    // 'workstation_id' => ['text' => 'string|nullable|exists:workstations,alias'],
                    // 'workstation_overridden_id' => ['text' => 'string|nullable|exists:workstations,alias'],
                    // 'phase_id' => [
                    //     'type' => 'select',
                    //     'multiple' => false,
                    //     'rules' => 'string|nullable|exists:products__phases,id',
                    //     'relation' => 'phase'
                    // ],
                ],
                'width' => ['1-2@m']
            ],
        ];
    }
}
