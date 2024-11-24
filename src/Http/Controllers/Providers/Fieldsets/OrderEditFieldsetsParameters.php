<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function array_keys;
use function config;
use function implode;

class OrderEditFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		$fieldsToUpdate = [
			'calculated_production_days',
			'calculated_event_days',
			'total_reimbursements_cost',
			'total_vehicles_cost',
			'total_operators_cost',
			'total_daily_allowances_cost',
			'total_hotels_cost',
			'total_rents_cost',
			'total_costs',
			'total_proposal',
			'total_gain',
		];

		$mainDataFields = [
			'name' => [
				'type' => 'text',
				'label' => 'Preventivo',
				'rules' => 'string|required'
			],
			'client_id' => [
				'readOnly' => true,
				'type' => 'select',
				'select2' => false,
				'multiple' => false,
				'rules' => 'string|nullable|exists:' . config('clients.models.client.table') . ',id',
				'relation' => 'client'
			],
			'project_id' => [
				'type' => 'select',
				'multiple' => false,
				'rules' => 'string|nullable|exists:' . config('products.models.project.table') . ',id',
				'relation' => 'project'
			],
			'event' => ['text' => 'string|required|max:128'],
			//					'description' => ['textarea' => 'string|nullable|max:1024'],

			//					'category' => [
			//						'type' => 'select',
			//						'multiple' => false,
			//						'rules' => 'string|nullable|exists:' . config('category.models.category.table') . ',id',
			//						'relation' => 'category'
			//					],
			//					'parent_id' => [
			//						'readOnly' => true,
			//						'type' => 'select',
			//						'select2' => false,
			//						'multiple' => false,
			//						'rules' => 'string|nullable|exists:' . config('products.models.quotation.table') . ',id',
			//						'relation' => 'parent'
			//					],
		];

		$result = [
			'mainData' => [
				'translationPrefix' => 'products::fields',
				'fields' => $mainDataFields,
				'width' => ['large']
			],
			'tempistiche' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'half_start' => [
						'type' => 'booleanCheckbox',
						'rules' => 'boolean|nullable',
						'data' => ['reloadalltables' => true],
						'fetchFieldValue' => $fieldsToUpdate,
						'widthClass' => 'uk-width-auto',
						'vertical' => true
					],

					'starts_at' => [
						'type' => 'date',
						'rules' => 'date|nullable',
						'data' => ['reloadalltables' => true],
						'fetchFieldValue' => $fieldsToUpdate,
						'widthClass' => 'uk-width-1-3',
						'vertical' => true
					],
					'ends_at' => [
						'type' => 'date',
						'rules' => 'date|nullable',
						'data' => ['reloadalltables' => true],
						'fetchFieldValue' => $fieldsToUpdate,
						'widthClass' => 'uk-width-1-3',
						'vertical' => true
					],
					'half_end' => [
						'type' => 'booleanCheckbox',
						'rules' => 'boolean|nullable',
						'data' => ['reloadalltables' => true],
						'fetchFieldValue' => $fieldsToUpdate,
						'widthClass' => 'uk-width-auto',
						'vertical' => true
					],
					'calculated_production_days' => [
						'type' => 'number',
						'rules' => 'numeric|nullable',
						'widthClass' => 'uk-width-expand',
						'vertical' => true
					],
					'event_starts_at' => [
						'type' => 'date',
						'rules' => 'date|nullable',
						'data' => ['reloadalltables' => true],
						'fetchFieldValue' => $fieldsToUpdate,
						'widthClass' => 'uk-width-1-3',
						'vertical' => true
					],
					'event_ends_at' => [
						'type' => 'date',
						'rules' => 'date|nullable',
						'data' => ['reloadalltables' => true],
						'fetchFieldValue' => $fieldsToUpdate,
						'widthClass' => 'uk-width-1-3',
						'vertical' => true
					],
					'calculated_event_days' => [
						'type' => 'number',
						'rules' => 'numeric|nullable',
						'widthClass' => 'uk-width-expand',
						'vertical' => true
					],

				],
				'width' => ['xlarge']
			],
			'luogo' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'destination_id' => [
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'rules' => 'string|nullable|in:' . implode(',', array_keys($this->getModel()->getPossibleDestinationsValuesArray())),
						'relation' => 'destination',
						'data' => ['reloadalltables' => true],
						'fetchFieldValue' => [
							'national',
							'daily_allowance',
							'street',
							'number',
							'zip',
							'city',
							'province',
							'state'
						]
					],
					'street' => [
						'type' => 'text',
						'rules' => 'string|nullable|max:128',
						'widthClass' => 'uk-width-2-3',
						'vertical' => true

					],
					'number' => [
						'type' => 'text',
						'rules' => 'string|nullable|max:128',
						'widthClass' => 'uk-width-1-3',
						'vertical' => true
					],
					'zip' => [
						'type' => 'text',
						'rules' => 'string|nullable|max:128',
						'widthClass' => 'uk-width-1-5',
						'vertical' => true,
					],
					'city' => [
						'type' => 'text',
						'rules' => 'string|nullable|max:128',
						'widthClass' => 'uk-width-3-5',
						'vertical' => true,
					],
					'province' => [
						'type' => 'text',
						'rules' => 'string|nullable|max:128',
						'widthClass' => 'uk-width-1-5',
						'vertical' => true,
					],
					'state' => [
						'type' => 'text',
						'rules' => 'string|nullable|max:128',
						'fetchFieldValue' => [
							'national',
							'daily_allowance',
						],
						'widthClass' => 'uk-width-4-5',
						'vertical' => true,
					],

					'national' => [
						'readOnly' => true,
						'type' => 'boolean',
						'visible' => false,
						'rules' => 'boolean|required',
						'widthClass' => 'uk-width-1-3',
						'vertical' => true,
					],
					'daily_allowance' => [
						'type' => 'number',
						'data' => ['reloadalltables' => true],
						'rules' => 'numeric|nullable',
						'widthClass' => 'uk-width-1-5',
						'vertical' => true,
					],
					'km' => [
						'type' => 'number',
						'rules' => 'numeric|nullable',
						'data' => ['reloadalltables' => true],
						'widthClass' => 'uk-width-3-5',
						'vertical' => true
					],
					'round_trip' => [
						'type' => 'booleanCheckbox',
						'rules' => 'boolean|required',
						'data' => ['reloadalltables' => true],
						'widthClass' => 'uk-width-1-5',
						'vertical' => true
					],
					'toll' => [
						'type' => 'number',
						'rules' => 'numeric|nullable',
						'data' => ['reloadalltables' => true],
						'widthClass' => 'uk-width-1-5',
						'vertical' => true
					],
				],
				'width' => ['large']
			],
			'costi' => [
				'translationPrefix' => 'products::fields',
				'fields' => [

					'total_reimbursements_cost' => [
						'type' => 'money',
						'step' => '0.01',
						'rules' => 'numeric|nullable',
						'readOnly' => true
					],
					'total_vehicles_cost' => [
						'type' => 'money',
						'rules' => 'numeric|nullable',
						'readOnly' => true
					],
					'total_operators_cost' => [
						'type' => 'money',
						'rules' => 'numeric|nullable',
						'readOnly' => true
					],
					'total_daily_allowances_cost' => [
						'type' => 'money',
						'rules' => 'numeric|nullable',
						'readOnly' => true
					],
					'total_hotels_cost' => [
						'type' => 'money',
						'rules' => 'numeric|nullable',
						'readOnly' => true
					],
					'total_rents_cost' => [
						'type' => 'money',
						'rules' => 'numeric|nullable',
						'readOnly' => true
					],
					'total_costs' => [
						'type' => 'money',
						'rules' => 'numeric|nullable',
						'readOnly' => true
					],

					'total_proposal' => [
						'type' => 'money',
						'rules' => 'numeric|nullable',
						'fetchFieldValue' => [
							'total_gain'
						]
					],
					'total_gain' => [
						'type' => 'money',
						'rules' => 'numeric|nullable',
						'readOnly' => true
					],
				],
				'width' => ['medium']
			]
		];

		$result['mainData']['view'] = [
			'name' => 'scripts.quotationPage',
		];

		//		$result['mainData']['fields'] = static::insertFieldsInPosition(['event' => ['text' => 'string|required|max:128']], $result['mainData']['fields'], 3);

		//		$result['mainData']['fields']['client_id']['label'] = 'Cliente';

		//		$result['tempistiche']['fields']['starts_at'] = [
		//			'type' => 'date',
		//			'label' => 'Inizio',
		//			'rules' => 'date|nullable',
		//			'data' => ['reloadalltables' => true],
		//			'fetchFieldValue' => $fieldsToUpdate
		//		];

		//		$result['tempistiche']['fields']['ends_at'] = [
		//			'type' => 'date',
		//			'label' => 'Fine',
		//			'rules' => 'date|nullable',
		//			'data' => ['reloadalltables' => true],
		//			'fetchFieldValue' => $fieldsToUpdate
		//		];

		//		$result['tempistiche']['fields']['half_start'] = [
		//			'type' => 'boolean',
		//			'rules' => 'boolean|nullable',
		//			'data' => ['reloadalltables' => true],
		//			'fetchFieldValue' => $fieldsToUpdate
		//		];

		//		$result['tempistiche']['fields']['half_end'] = [
		//			'type' => 'boolean',
		//			'rules' => 'boolean|nullable',
		//			'data' => ['reloadalltables' => true],
		//			'fetchFieldValue' => $fieldsToUpdate
		//		];

		//		$result['tempistiche']['fields']['calculated_production_days'] = ['number' => 'numeric|nullable'];

		//		$result['tempistiche']['fields']['event_starts_at'] = [
		//			'type' => 'date',
		//			'rules' => 'date|nullable',
		//			'data' => ['reloadalltables' => true],
		//			'fetchFieldValue' => $fieldsToUpdate
		//		];

		//		$result['tempistiche']['fields']['event_ends_at'] = [
		//			'type' => 'date',
		//			'rules' => 'date|nullable',
		//			'data' => ['reloadalltables' => true],
		//			'fetchFieldValue' => $fieldsToUpdate
		//		];

		foreach ($result as $fieldset => $parameters)
			$result[$fieldset]['showLegend'] = false;

		//		unset($result['mainData']['fields']['parent_id']);
		//		unset($result['mainData']['fields']['category']);
		//		unset($result['mainData']['fields']['description']);

		//		$tollParameters = [
		//			'type' => 'number',
		//			'rules' => 'numeric|nullable',
		//			'data' => ['reloadalltables' => true],
		////			'widthClass' => 'uk-width-1-3',
		////			'vertical' => true
		//		];
		//
		//		$result['luogo']['fields'] = static::insertFieldsInPosition(['toll' => $tollParameters], $result['luogo']['fields'], 4);

		//		$result['tempistiche']['fields']['calculated_event_days'] = ['number' => 'numeric|nullable'];
		//		$result['tempistiche']['view'] = [
		//			'name' => 'products::quotations.scripts',
		//		];

		return $result;
	}
}
