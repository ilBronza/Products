<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\RowsHelpers\CostsFieldsGroupParametersFile;
use IlBronza\Products\Providers\Helpers\Sellables\SellablePriceDatatableFieldsHelper;
use Illuminate\Database\Eloquent\Model;

class RowsFieldsGroupParametersFile extends CostsFieldsGroupParametersFile
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
			'mySelfEdit' => [
				'type' => 'links.edit',
				'fieldsGroupsDefinitions' => [
					'managementParameters'
				],
			],

			'sellableSupplier.supplier.target' => [
				'type' => 'links.seeName',
				'icon' => false,
				'width' => '180px',
				'fieldsGroupsDefinitions' => [
					'managementParameters'
				],
			],
			'sellable.name' => [
				'type' => 'flat',
				'width' => '18em'
			],
			'mySelfChangeSellableSupplier' => [
				'type' => 'products::sellableSuppliers.changeSellableSupplier',
				'fieldsGroupsDefinitions' => [
					'managementParameters'
				],
			],

			'description' => [
				'type' => 'editor.text',
				'width' => '20em'
			],

			'client_description' => [
				'type' => 'editor.text',
				'width' => '20em',
				'fieldsGroupsDefinitions' => [
					'pdfManagement'
				],
			],

			'starts_at' => [
				'type' => 'editor.dates.date',
				'fieldsGroupsDefinitions' => [
					'managementParameters'
				],
			],
			'ends_at' => [
				'type' => 'editor.dates.date',
				'fieldsGroupsDefinitions' => [
					'managementParameters'
				],
			],

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

	static function getPdfParameters() : array
	{
		return [
			'type' => 'editor.toggle',
			'fieldsGroupsDefinitions' => [
				'pdfManagement'
			],
		];
	}

	static function addPdfFields(array $fields) : array
	{
		$fields['pdf_quotation_show'] = static::getPdfParameters();
		$fields['pdf_quotation_show_price'] = static::getPdfParameters();
		$fields['pdf_quotation_show_quantity'] = static::getPdfParameters();

		return $fields;
	}

	static function addCostsFields(array $fields, $model) : array
	{
		$fields['calculated_cost_coefficient'] = [
					'type' => 'editor.numeric',
					'refreshRow' => true,
					'fieldsGroupsDefinitions' => [
						'costs'
					],
				];

		$fields['calculated_revenue_coefficient'] = [
					'type' => 'editor.numeric',
					'refreshRow' => true,
					'fieldsGroupsDefinitions' => [
						'revenue'
					],
				];

		$fields = static::addCostsFieldsByModel(
			$fields,
			$model
		);

		$fields = static::addSummaryCostsFields($fields);

		$fields['calculated_vat'] = [
					'type' => 'editor.numeric',
					'fieldsGroupsDefinitions' => [
						'costs'
					],
				];

		$fields['calculated_vat_cost'] = [
					'type' => 'editor.numeric',
					'fieldsGroupsDefinitions' => [
						'costs'
					],
				];

		$fields['mySelfDelete'] = 'links.delete';

		return $fields;
	}

	static function getPriceFields(array $fieldsGroupDefinitions = [])
	{
		return [
			'type' => 'editor.price',
			'refreshRow' => true,
			'fieldsGroupsDefinitions' => $fieldsGroupDefinitions
		];		
	}

	static function addDiscountFields(array $fields) : array
	{
		$fields['discount_neat'] = [
			'type' => 'editor.numeric',
			'refreshRow' => true,
			'rules' => 'numeric|nullable|min:0',
			'fieldsGroupsDefinitions' => [
				'revenue',
			],
		];

		$fields['discount_percentage'] = [
			'type' => 'editor.numeric',
			'refreshRow' => true,
			'rules' => 'numeric|nullable|min:0|max:100',
			'fieldsGroupsDefinitions' => [
				'revenue',
			],
		];

		return $fields;
	}

	static function addSummaryCostsFields(array $fields) : array
	{
		$priceField = [
			'type' => 'editor.price',
			'refreshRow' => true
		];

		foreach([
			'calculated_single_cost' => static::getPriceFields(['costs']),
			'calculated_single_revenue' => static::getPriceFields(),

			'calculated_total_row_cost' => static::getPriceFields(['costs']),
			'approved_total_row_cost' => [
				'type' => 'editor.toggle',
				'fieldsGroupsDefinitions' => ['costs']
			],

			'calculated_total_row_revenue' => static::getPriceFields(),
			'approved_total_row_revenue' => 'editor.toggle'
		] as $field => $parameters)
			$fields[$field] = $parameters;

		return static::addDiscountFields($fields);
	}
}