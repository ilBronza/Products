<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RowsCostsFieldsHelper
{
	public ProductPackageBaseRowcontainerModel $containerModel;
	public string $rowsRelationName;

	public float $totalCosts = 0;
	public float $totalRevenues = 0;
	public float $margin = 0;
	public float $marginPercentage = 0;
	public string $fieldPrefix;

	public function __construct(ProductPackageBaseRowcontainerModel $containerModel, string $rowsRelationName)
	{
		$this->containerModel = $containerModel;
		$this->rowsRelationName = $rowsRelationName;

		$this->fieldPrefix = Str::snake($rowsRelationName);
	}

	/**
	 * Esempio:
	 * RowsCostsFieldsHelper::getFormFieldsetsByRowsType($containerModel, 'operatorRows')
	 */
	static function getFormFieldsetsByRowsType(ProductPackageBaseRowcontainerModel $containerModel, string $rowsRelationName) : array
	{
		$helper = new static($containerModel, $rowsRelationName);

		return $helper->getFormFieldsetParameters();
	}

	public function getParameters(float $value, array $parameters = []) : array
	{
		$result = [
			'type' => 'money',
			'step' => '0.01',
			'rules' => 'numeric|nullable',
			'widthClass' => 'uk-width-3-5',
			'readOnly' => true,
			'vertical' => true,
			'showLabel' => false,
			'value' => $value
		];

		foreach($parameters as $key => $value)
			$result[$key] = $value;

		return $result;
	}

	static function getCostFieldName(string $relationName)
	{
		return 'total_' . Str::snake($relationName) . '_cost';
	}

	static function getRevenueFieldName(string $relationName)
	{
		return 'total_' . Str::snake($relationName) . '_revenue';
	}

	static function getMarginFieldName(string $relationName)
	{
		return 'margin_' . Str::snake($relationName);
	}

	static function getPercentageMarginFieldName(string $relationName)
	{
		return 'percentage_margin_' . Str::snake($relationName);
	}

	static function getRowCostsFieldsByRelation(string $rowsRelationName) : array
	{
		return [
			static::getCostFieldName($rowsRelationName),
			static::getRevenueFieldName($rowsRelationName),
			static::getMarginFieldName($rowsRelationName),
			static::getPercentageMarginFieldName($rowsRelationName)
		];
	}

	public function getFormFieldsetParameters() : array
	{
		$fields = [];

		foreach(static::getRowCostsFieldsByRelation($this->rowsRelationName) as $field)
		{
			try
			{
				//CalculatedTotalCostExtraField::class
				//chiama getTotalByCustomRowsCost()

				$fields[$field] = $this->getParameters(
					$this->containerModel->$field
				);				
			}
			catch(\Throwable $e)
			{
				dd($e->getMessage(), $field, $this, static::getRowCostsFieldsByRelation($this->rowsRelationName));
			}
		}

		$fields[static::getPercentageMarginFieldName($this->rowsRelationName)]['widthClass'] = 'uk-width-2-5';

		return [
			'translationPrefix' => 'products::fields',
			'canBeHidden' => false,
			'fields' => $fields,
			'width' => ['small']
		];
	}

	public function getRows() : Collection
	{
		return $this->containerModel->{$this->rowsRelationName};
	}

	static function getMupFormFieldsets(ProductPackageBaseRowcontainerModel $containerModel)
	{
		return [
			'canBeHidden' => false,
			'fields' => [
				'mup_selection' => [
					'type' => 'select',
					'select2' => false,
					'showLabel' => false,
					'multiple' => false,
					'vertical' => true,
					'rules' => 'string|nullable',
					'list' => [
						'mup_plus_extra' => 'Mup + servizi extra',
						'mup_forfait' => 'Ricavo concordato'
					],
					'default' => 'mup_forfait',
					'fetchFieldValue' => static::getTotalsFetchFieldValue(),
				],
				'mup_revenue' => [
					'type' => 'money',
					'rules' => 'numeric|nullable',
					'data' => ['reloadalltables' => true],
					'vertical' => true,
					'showLabel' => false,
					'fetchFieldValue' => static::getTotalsFetchFieldValue(),
				],
			],
			'width' => ['small']
		];
	}

	static function getDiscountFormFieldsets(ProductPackageBaseRowcontainerModel $containerModel) : array
	{
		return [
			'translationPrefix' => 'products::fields',
			'canBeHidden' => false,
			'fields' => [
				'discount_selection' => [
					'type' => 'select',
					'select2' => false,
					'showLabel' => false,
					'multiple' => false,
					'vertical' => true,
					'rules' => 'string|nullable|in:discount_neat,discount_percentage',
					'list' => [
						'discount_neat' => 'Sconto netto',
						'discount_percentage' => 'Sconto %',
					],
					'default' => 'discount_neat',
					'fetchFieldValue' => static::getTotalsFetchFieldValue(),
				],
				'discount_neat' => [
					'type' => 'money',
					'rules' => 'numeric|nullable|min:0',
					'data' => ['reloadalltables' => true],
					'vertical' => true,
					'showLabel' => false,
					'fetchFieldValue' => static::getTotalsFetchFieldValue(),
				],
				'discount_percentage' => [
					'type' => 'number',
					'step' => '0.01',
					'rules' => 'numeric|nullable|min:0|max:100',
					'data' => ['reloadalltables' => true],
					'vertical' => true,
					'showLabel' => false,
					'fetchFieldValue' => static::getTotalsFetchFieldValue(),
				],
			],
			'width' => ['small']
		];
	}

	static function getTotalsFetchFieldValue() : array
	{
		return [
			'total_revenue',
			'total_cost',
			'total_margin',
			'total_percentage_margin',
			'horizontal_total_revenue',
			'horizontal_total_cost',
			'horizontal_total_margin',
			'horizontal_total_percentage_margin',
		];
	}
}

