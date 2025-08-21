<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function config;

class ProductRelationCreateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
        return [
            'base' => [
				'translationPrefix' => 'products::fields',
                'fields' => [
	                'child_id' => [
		                'type' => 'select',
		                'multiple' => false,
		                'rules' => 'string|nullable|exists:' . config('products.models.product.table') . ',id',
		                'relation' => 'child',
	                ],
	                'parent_id' => [
		                'type' => 'text',
		                'visible' => false,
		                'value' => $this->getModel()->product_id,
		                'rules' => 'string|nullable|exists:' . config('products.models.product.table') . ',id',
		                'relation' => 'parent'
	                ],

	                'measurement_unit_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|nullable|exists:' . config('measurementUnits.models.measurementUnit.table') . ',id',
						'relation' => 'measurementUnit',
					],
	                'quantity_coefficient' => ['number' => 'integer|required|max:65535|min:0'],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}
