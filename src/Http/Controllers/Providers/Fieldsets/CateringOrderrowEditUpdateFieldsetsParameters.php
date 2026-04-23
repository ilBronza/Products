<?php

namespace IlBronza\Products\Http\Controllers\Providers\Fieldsets;

use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowFieldsetParametersFile;

class CateringOrderrowEditUpdateFieldsetsParameters extends RowFieldsetParametersFile
{
	public function _getFieldsetsParameters() : array
	{
		$containerModel = $this->getModel()->getModelContainer();

		$result = [
			'main' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'quantity' => ['number' => 'numeric|nullable'],
					'calculated_quantity_coefficient' => ['number' => 'numeric|nullable'],
					'calculated_cost_coefficient' => ['number' => 'numeric|nullable'],
					'calculated_revenue_coefficient' => ['number' => 'numeric|nullable'],

					'served_at_table' => ['boolean' => 'bool|nullable'],

					'people_coefficient' => [
						'type' => 'select',
						'rules' => 'string|nullable',
						'list' => $containerModel->getPossiblePeopleCoefficientArrayValues()
					],
					'phase' => [
						'type' => 'select',
						'rules' => 'string|nullable',
						'list' => $containerModel->getPossiblePhasesArrayValues()
					],
					'quantity_coefficient' => ['number' => 'numeric|nullable'],
				],
				'width' => ["1-3@l", '1-2@m']
			],
			'description' => [
				'translationPrefix' => 'products::fields',
				'fields' => [
					'description' => ['text' => 'string|nullable|max:128'],
					'client_description' => ['text' => 'string|nullable|max:128'],
				],
				'width' => ["1-3@l", '1-2@m']
			],
		];

		$result = static::addRowCostsFieldset(
			$result,
			Product::gpc()::make()
		);

		return $result;
	}
}
