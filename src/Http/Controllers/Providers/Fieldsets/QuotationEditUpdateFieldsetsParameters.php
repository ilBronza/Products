<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function config;

class QuotationEditUpdateFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		return [
			'package' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'name' => ['text' => 'string|required'],
					'slug' => ['text' => 'string|required'],
					'category' => [
						'type' => 'select',
						'multiple' => false,
						'rules' => 'string|required|exists:' . config('category.models.category.table') . ',id',
						'relation' => 'category'
					],
					'parent_id' => [
						'readOnly' => true,
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'rules' => 'string|nullable|exists:' . config('products.models.quotation.table') . ',id',
						'relation' => 'parent'
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
					'description' => ['textarea' => 'string|nullable|max:1024'],
					// 'client' => [
					//     'type' => 'select',
					//     'multiple' => false,
					//     'rules' => 'string|required|exists:' . config('clients.models.client.table') . ',id',
					//     'relation' => 'client'
					// ],
				],
				'width' => ["large"]
			],
			'tempistiche' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'starts_at' => ['date' => 'date|nullable'],
					'ends_at' => ['date' => 'date|nullable'],
				],
				'width' => ['medium']
			],
			'luogo' => [
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
					'national' => [
						'readOnly' => true,
						'type' => 'boolean',
						'rules'=> 'boolean|nullable'
					],
					'km' => [
						'type' => 'number',
						'rules'=> 'numeric|nullable',
						'data' => ['reloadalltables' => true],
					],
					'round_trip' => [
						'type' => 'boolean',
						'rules'=> 'boolean|nullable',
						'data' => ['reloadalltables' => true],
					],
					'daily_allowance' => [
						'type' => 'number',
						'data' => ['reloadalltables' => true],
						'rules'=> 'numeric|nullable'
					],
				],
				'width' => ['large']
			],
			'costi' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'tot_ricavo' => ['number' => 'numeric|nullable'],
					'tot_proposto' => ['number' => 'numeric|nullable'],
					'tot_preventivo' => ['number' => 'numeric|nullable'],
				],
				'width' => ['medium']
			]
		];
	}
}
