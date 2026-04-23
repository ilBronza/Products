<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Datatables\Providers\FieldsGroupParametersFile;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\Sellables\SellablePriceDatatableFieldsHelper;
use Illuminate\Database\Eloquent\Model;

class RowsFieldsGroupParametersFile extends FieldsGroupParametersFile
{
	public ProductPackageBaseRowcontainerModel $containerModel;

	static function gpc() : string
	{
		return config('products.helpers.rowsFieldsGroupParametersFile');
	}

	public function getRowStartingFields()
	{
		return [
			'mySelfPrimary' => 'primary',
			'sorting_index' => [
				'type' => 'flat',
				'order' => [
					'priority' => 10
				],
				'width' => '2em'
			],
			'mySelfEdit' => 'links.edit',

			'sellableSupplier.supplier.target' => [
				'type' => 'links.seeName',
				'icon' => false,
				'width' => '180px',
			],
			'sellable.name' => 'flat',
			'mySelfChangeSellableSupplier' => 'products::sellableSuppliers.changeSellableSupplier',

			'description' => [
				'type' => 'editor.text',
				'width' => '20em'
			],

			'client_description' => [
				'type' => 'editor.text',
				'width' => '20em'
			],

			'starts_at' => 'editor.dates.date',
			'ends_at' => 'editor.dates.date',

			'quantity' => [
				'type' => 'editor.numeric',
				'refreshRow' => true,
			],
		];
	}

	public function setContainerModel(ProductPackageBaseRowcontainerModel $containerModel)
	{
		$this->containerModel = $containerModel;
	}

	static function createByContainer(ProductPackageBaseRowcontainerModel $containerModel)
	{
		$class = static::gpc();

		$helper = new $class();

		$helper->setContainerModel($containerModel);

		return $helper;
	}

	static function addCostsFields(array $fields, $model) : array
	{
		$fields['calculated_cost_coefficient'] = [
					'type' => 'editor.numeric',
					'refreshRow' => true,
				];

		$fields['calculated_revenue_coefficient'] = [
					'type' => 'editor.numeric',
					'refreshRow' => true,
				];

		$fields = static::addCostsFieldsByModel(
			$fields,
			$model
		);

		$fields = static::addSummaryCostsFields($fields);

		$fields['mySelfDelete'] = 'links.delete';

		return $fields;
	}

	static function addCostsFieldsByModel(array $fields, Model $model) : array
	{
		if (! $model instanceof SellableItemInterface)
			return $parameters;

		$fields = array_merge(
			$fields, 
			SellablePriceDatatableFieldsHelper::getCalculatedFieldsByModel(
				$model
			)
		);

		return $fields;
	}

	static function addSummaryCostsFields(array $fields) : array
	{
		$priceField = [
			'type' => 'editor.price',
			'refreshRow' => true
		];

		foreach([
			'calculated_single_cost' => $priceField,
			'calculated_single_revenue' => $priceField,

			'calculated_total_row_cost' => $priceField,
			'approved_total_row_cost' => 'editor.toggle',

			'calculated_total_row_revenue' => $priceField,
			'approved_total_row_revenue' => 'editor.toggle'
		] as $field => $parameters)
			$fields[$field] = $parameters;

		return $fields;
	}
}