<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use function config;

class OrderrowBulkEditUpdateFieldsetsParameters extends BaserowEditUpdateFieldsetsParameters
{
	public function _getFieldsetsParameters() : array
	{
		return [
			'base' => [
				'translationPrefix' => 'products::fields',
				'fields' => [

				],
			],
		];
	}
}
