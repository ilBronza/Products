<?php

namespace IlBronza\Products\Http\Parameters\Forms\Catering;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowContainerCostsFieldsHelper;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsCostsFieldsHelper;

class CateringOrderEditUpdateFieldsetsParametersFile extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		$result = [
			'mainData' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
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
					'description' => ['textarea' => 'string|nullable|max:1024'],

					'category' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|nullable|exists:' . config('category.models.category.table') . ',id',
						'relation' => 'category'
					],
					'cost_coefficient' => [
						'type' => 'number',
						'data' => [
							'reloadalltables' => true
						],
						'vertical' => true,
						'rules' => 'numeric|nullable'
					],
					'revenue_coefficient' => [
						'type' => 'number',
						'data' => [
							'reloadalltables' => true
						],
						'vertical' => true,
						'rules' => 'numeric|nullable'
					],
					'state_id' => [
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'rules' => 'string|nullable',
						'list' => [
							0 => 'In Corso',
							1 => 'Accettato',
							2 => 'Inviato',
							3 => 'Da modificare',
							4 => 'Rifiutato',
							5 => 'Sbagliato',
							6 => 'Eliminato',
							7 => 'Template',
						]
					],
					
				],
				'width' => ['large']
			],
			'parameters' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'starts_at' => [
						'type' => (config('products.models.order.usesHours', false)) ? 'datetime' : 'date',
						'rules' => [
							'date',
							'nullable'
						],
						'vertical' => true,
						'data' => ['reloadalltables' => true],
						'widthClass' => 'uk-width-2-5',
					],
					'ends_at' => [
						'type' => (config('products.models.order.usesHours', false)) ? 'datetime' : 'date',
						'rules' => [
							'date',
							'nullable',
							'after:starts_at'
						],
						'vertical' => true,
						'data' => ['reloadalltables' => true],
						'widthClass' => 'uk-width-2-5',
					],
					'base_quantity' => [
						'type' => 'number',
						'data' => [
							'reloadalltables' => true
						],
						'vertical' => true,
						'widthClass' => 'uk-width-1-5',
						'rules' => 'numeric|nullable'
					],
                    'people_coefficient' => [
                        'type' => 'json',
                        'fields' => [
                            'name' => ['text' => 'string|nullable|min:1|max:64'],
                            'quantity' => ['number' => 'integer|nullable|min:1|max:999'],
                            'price_coefficient' => ['text' => 'numeric|nullable|min:0|max:9'],
                            'calculated_price' => ['text' => 'numeric|nullable|min:0|max:9'],
                            'price' => ['text' => 'numeric|nullable|min:0|max:9'],
                        ],
                        'rules' => 'array|nullable',
                    ],
                    'phases' => [
                        'type' => 'json',
                        'fields' => [
                            'name' => ['text' => 'string|nullable|min:1|max:64'],
                            'starts_at' => [
                            	'type' => 'time',
                            	'rules' => [
                            		'nullable', 
                            		'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'
                            	]
                            ],
                            'ends_at' => [
                            	'type' => 'time',
                            	'rules' => [
                            		'nullable', 
                            		'regex:/^(?:[01]\d|2[0-3]):[0-5]\d$/'
                            	]
                            ],
                        ],
                        'rules' => 'array|nullable',
                    ],

				],
				'width' => ['xlarge']
			],
			'destination' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'destination_id' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|nullable|exists:' . config('clients.models.destination.table') . ',id',
						'relation' => 'destination',
						'data' => ['reloadalltables' => true],
						'fetchFieldValue' => [
							'national',
							'daily_allowance'
						]
					],
					'km' => [
						'type' => 'number',
						'rules' => 'numeric|nullable',
						'data' => ['reloadalltables' => true],
					],
				],
				'width' => ['large']
			],
			'economicalsSummary' => [
				'translationPrefix' => 'products::fields',
				'fields' => [],
				'width' => ['auto'],
				'view' => [
					'name' => 'products::scripts.orderQuotationPage',
				],
				'fieldsets' => [
					'productsCosts' => RowsCostsFieldsHelper::getFormFieldsetsByRowsType($this->getModel(), 'productRows'),
					'vehiclesCosts' => RowsCostsFieldsHelper::getFormFieldsetsByRowsType($this->getModel(), 'vehicleRows'),
					'operatorsCosts' => RowsCostsFieldsHelper::getFormFieldsetsByRowsType($this->getModel(), 'operatorRows'),
					'accessoriesCosts' => RowsCostsFieldsHelper::getFormFieldsetsByRowsType($this->getModel(), 'accessoryRows'),
					'mup' => RowsCostsFieldsHelper::getMupFormFieldsets($this->getModel()),




					'discounts' => RowsCostsFieldsHelper::getDiscountFormFieldsets($this->getModel()),

					// 'mup' => [
					// 	'canBeHidden' => false,
					// 	'fields' => [
					// 		'mup_cost' => [
					// 			'type' => 'number',
					// 			'rules' => 'numeric|nullable',
					// 			'data' => ['reloadalltables' => true],
					// 			'vertical' => true,
					// 			'showLabel' => false,
					// 		],
					// 		'mup_revenue' => [
					// 			'type' => 'number',
					// 			'rules' => 'numeric|nullable',
					// 			'data' => ['reloadalltables' => true],
					// 			'vertical' => true,
					// 			'showLabel' => false,
					// 		],
					// 	],
					// 	'width' => ['small']
					// ],
					'totals' => RowContainerCostsFieldsHelper::getFormFieldsetsByRowsTypes($this->getModel(), [
						'productRows',
						'vehicleRows',
						'operatorRows',
						'accessoryRows'
					])
				]
			]
		];

		return $result;
	}
}
