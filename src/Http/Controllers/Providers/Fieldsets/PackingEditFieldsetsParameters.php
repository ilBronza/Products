<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

class PackingEditFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'package' => [
                'fields' => [
                    'package_width' => ['number' => 'integer|nullable|min:0'],
                    'package_height' => ['number' => 'integer|nullable|min:0'],
                    'package_length' => ['number' => 'integer|nullable|min:0'],
                    'package_weight' => ['number' => 'decimal|nullable|min:0'],
                    'package_volume_mq' => ['number' => 'decimal|nullable|min:0'],
                    'quantity_per_package' => ['number' => 'decimal|nullable|min:0'],
                ],
                'width' => ["1-4@l", '1-2@m']
            ],
            'packing' => [
                'fields' => [
                    'packing_width' => ['number' => 'integer|nullable|min:0'],
                    'packing_height' => ['number' => 'integer|nullable|min:0'],
                    'packing_lenght' => ['number' => 'integer|nullable|min:0'],
                    'packing_weight' => ['number' => 'decimal|nullable|min:0'],
                    'packing_volume_mq' => ['number' => 'decimal|nullable|min:0'],
                ],
                'width' => ["1-4@l", '1-2@m']
            ],
            'total' => [
                'fields' => [
                    'pallettype_id' => [
                        'type' => 'select',
                        'multiple' => false,
                        'rules' => 'string|nullable|exists:warehouse__pallettypes,id',
                        'relation' => 'pallettype'
                    ],
                    'package_per_layer' => ['number' => 'integer|nullable|min:0'],
                    'layers_per_packing' => ['number' => 'integer|nullable|min:0'],
                    'quantity_per_packing' => ['number' => 'integer|nullable|min:0'],
                    'pack_notes' => ['text' => 'string|nullable|max:191'],
                ],
                'width' => ["1-4@l", '1-2@m']
            ],
            'images' => [
                'fields' => [
                    'packing_images' => [
                        'type' => 'file',
                        'multiple' => true,
                        'rules' =>'string|nullable|max:2048'],
                ],
                'width' => ["1-4@l", '1-2@m']
            ]
        ];
    }
}
