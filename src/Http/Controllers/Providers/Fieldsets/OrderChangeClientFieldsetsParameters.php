<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;

use function config;

class OrderChangeClientFieldsetsParameters extends FieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		return [
			'mainData' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'client_id' => [
						'type' => 'select',
						'rules' => 'string|required|exists:' . config('clients.models.client.table') . ',id',
						'relation' => 'client'
					]
				]
			]
		];
	}
}
