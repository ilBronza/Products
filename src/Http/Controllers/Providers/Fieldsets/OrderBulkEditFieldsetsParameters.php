<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use function config;

class OrderBulkEditFieldsetsParameters extends QuotationEditUpdateFieldsetsParameters
{
	public function _getFieldsetsParameters() : array
	{
		return [
			'mainData' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
//					'name' => [
//						'type' => 'text',
//						'label' => 'Nome',
//						'rules' => 'string|required'
//					],
					'client_id' => [
						'readOnly' => true,
						'type' => 'select',
						'select2' => false,
						'multiple' => false,
						'rules' => 'string|nullable|exists:' . config('clients.models.client.table') . ',id',
						'relation' => 'client'
					],
//					'project_id' => [
//						'type' => 'select',
//						'multiple' => false,
//						'rules' => 'string|nullable|exists:' . config('products.models.project.table') . ',id',
//						'relation' => 'project'
//					],
//					'description' => ['textarea' => 'string|nullable|max:1024'],
//
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
				],
				'width' => ['large']
			]
		];
	}
}
