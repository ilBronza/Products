<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use IlBronza\Products\Models\Workstation;

use function config;

class PhaseCreateFieldsetsParameters extends FieldsetParametersFile
{
    public function _getFieldsetsParameters() : array
    {
		$workstation = Workstation::gpc()::make();

        return [
            'base' => [
                'translationPrefix' => 'products::fields',
                'fields' => [
	                'product_id' => [
		                'type' => 'text',
//		                'visible' => false,
		                'value' => $this->getModel()->product_id,
		                'rules' => 'string|nullable|exists:' . config('products.models.product.table') . ',id',
		                'relation' => 'product'
	                ],
                    'name' => ['text' => 'string|nullable|max:255'],
                    'workstation_id' => [
                        'type' => 'select',
                        'multiple' => false,
                        'rules' => 'integer|nullable|exists:' . $workstation->getTable() . ',' . $workstation->getKeyName(),
                        'relation' => 'workstation',
                    ],
                    'sorting_index' => [
						'type' => 'number',
	                    'rules'=> 'numeric|nullable',
	                    'value' => ($this->getModel()?->getProduct()?->getPhases()?->max('sorting_index')) + 1,
                    ],
                    'coefficient_output' => ['number' => 'numeric|nullable'],
                    'slug' => ['text' => 'string|nullable|max:255'],
                ],
                'width' => ['1-2@m']
            ]
        ];
    }
}



