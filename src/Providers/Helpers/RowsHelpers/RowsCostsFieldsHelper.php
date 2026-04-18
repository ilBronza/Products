<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use function iterator_to_array;

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

	public function calculateTotals()
	{
		// $this->totalCosts = $this->containerModel->{$this->getCostFieldName($this->rowsRelationName)};
		// $this->totalRevenues = $this->containerModel->{$this->getRevenueFieldName($this->rowsRelationName)};
		// $this->margin = $this->containerModel->{$this->getMarginFieldName($this->rowsRelationName)};
		// $this->marginPercentage = $this->containerModel->{$this->getPercentageMarginFieldName($this->rowsRelationName)};
	}

	static function getRowCostsFieldsByRelation(string $rowsRelationName) : array
	{
		return [
			static::getRevenueFieldName($rowsRelationName),
			static::getCostFieldName($rowsRelationName),
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
				$fields[$field] = $this->getParameters(
					$this->containerModel->$field
				);				
			}
			catch(\Throwable $e)
			{
				dd($e->getMessage(), $field);
			}
		}

		$fields[static::getPercentageMarginFieldName($this->rowsRelationName)]['widthClass'] = 'uk-width-2-5';

		return [
			'translationPrefix' => 'products::fields',
			'fields' => $fields,
			'width' => ['small']
		];
	}

	public function getRows() : Collection
	{
		return $this->containerModel->{$this->rowsRelationName};
	}
}

