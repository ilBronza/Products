<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function config;

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

	                'round_trip' => ['boolean' => 'bool|nullable'],
	                'half_start' => ['boolean' => 'bool|nullable'],

	                'starts_at' => ['date' => 'date|nullable'],
	                'ends_at' => ['date' => 'date|nullable'],
	                'half_end' => ['boolean' => 'bool|nullable'],

	                'approved' => ['boolean' => 'bool|nullable'],

	                'quantity' => ['number' => 'numeric|nullable'],

	                'calculated_cost_company' => ['number' => 'numeric|nullable'],
//	                'calculated_client_price' => ['number' => 'numeric|nullable'],

	                'calculated_cost_company_total' => ['number' => 'numeric|nullable'],
//	                'calculated_client_price_total' => ['number' => 'numeric|nullable'],

	                'driver' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossibleDriversArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'first_passenger' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossiblePassengersArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'second_passenger' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossiblePassengersArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'third_passenger' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossiblePassengersArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'fourth_passenger' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossiblePassengersArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'fifth_passenger' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossiblePassengersArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'sixth_passenger' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossiblePassengersArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'seventh_passenger' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossiblePassengersArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'eighth_passenger' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossiblePassengersArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'reimbursement_from_place' => ['text' => 'string|nullable|max:255'],
	                'reimbursement_to_place' => ['text' => 'string|nullable|max:255'],
	                'reimbursement_annotations' => ['text' => 'string|nullable|max:1024'],

	                'reimbursement_operator' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossibleOperatorsArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'first_guest' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossibleOperatorsArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],

	                'second_guest' => [
		                'type' => 'select',
		                'multiple' => false,
		                'possibleValuesArray' => $this->getModel()->getPossibleOperatorsArrayValues(),
		                'rules' => 'string|nullable|exists:' . config('operators.models.operator.table') . ',id',
	                ],


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
