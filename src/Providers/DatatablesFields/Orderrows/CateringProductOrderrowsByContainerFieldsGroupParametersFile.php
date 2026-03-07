<?php

namespace IlBronza\Products\Providers\DatatablesFields\Orderrows;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use Illuminate\Database\Eloquent\Model;

class CateringProductOrderrowsByContainerFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	static function getFieldsGroup(Model $parentModel) : array
	{
		return [
			'translationPrefix' => 'products::fields',
			'summary' => [
				'calculated_client_price' => 'avg',
				'quantity' => 'sum',
				'total_cost' => 'sum',
				'total_client_price' => 'sum',
			],
			'fields' => [
				'mySelfPrimary' => 'primary',
				'sorting_index' => [
					'type' => 'flat',
					'order' => [
						'priority' => 10
					],
					'width' => '2em'
				],
				'people_coefficient' => [
					'type' => 'editor.select',
					'possibleValuesArray' => $parentModel->getPossiblePeopleCoefficientArrayValues(),
					'refreshRow' => true
				],
				'phase' => [
					'type' => 'editor.select',
					'possibleValuesArray' => $parentModel->getPossiblePhasesArrayValues(),
					'refreshRow' => true
				],
				'sellable' => 'products::sellables.sellable',

				'calculated_client_price' => [
					'type' => 'editor.numeric',
					'refreshRow' => true,
				],
				'different_client_price' => 'flatRowClass',

				'quantity_coefficient' => [
					'type' => 'editor.numeric',
					'refreshRow' => true,
				],
				'different_quantity_coefficient' => 'flatRowClass',

				'cost_coefficient' => [
					'type' => 'editor.numeric',
					'refreshRow' => true,
					//				'reloadTable' => true,
				],
				'different_cost_coefficient' => 'flatRowClass',

				'quantity' => [
					'type' => 'editor.numeric',
					'refreshRow' => true,
				],
				'different_quantity' => 'flatRowClass',

				'description' => [
					'type' => 'editor.text',
					'width' => '30em'
				],

				'quotation_description' => [
					'type' => 'editor.text',
					'width' => '30em'
				],

				'confirmed' => [
					'refreshRow' => true,
					//				'reloadTable' => true,
					'type' => 'editor.toggle',
				],

				'served_at_table' => [
					'refreshRow' => true,
					//				'reloadTable' => true,
					'type' => 'editor.toggle',
				],

				'total_cost' => 'numbers.price',
				'total_client_price' => 'numbers.price'
			]
		];
	}
}