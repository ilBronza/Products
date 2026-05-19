<?php

namespace IlBronza\Products\Providers\Helpers\RowsHelpers;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\CostCalculations\RowContainerCalculationsHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use function iterator_to_array;

class RowContainerCostsFieldsHelper
{
	public ProductPackageBaseRowcontainerModel $containerModel;

	public function __construct(ProductPackageBaseRowcontainerModel $containerModel)
	{
		$this->containerModel = $containerModel;

		// $this->fieldPrefix = Str::snake($rowsRelationName);
	}

	/**
	 * Esempio:
	 * RowsCostsFieldsHelper::getFormFieldsetsByRowsType($containerModel, 'operatorRows')
	 */
	static function getFormFieldsetsByRowsTypes(ProductPackageBaseRowcontainerModel $containerModel) : array
	{
		$helper = new static($containerModel);

		return $helper->getFormFieldsetParameters();
	}

	static function getHorizontalFormFieldsetsByRowsTypes(ProductPackageBaseRowcontainerModel $containerModel) : array
	{
		$helper = new static($containerModel);

		return $helper->getHorizontalFormFieldsetParameters();
	}

	public function getParameters(float $value = null, array $parameters = []) : array
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

	public function getFormFieldsetParameters() : array
	{
		$helper = RowContainerCalculationsHelper::create($this->containerModel);

		$fields = [
			'total_cost' => $this->getParameters(
				$helper->getTotalCostByRowTypes()
			),
			'total_revenue' => $this->getParameters(
				$helper->getTotalRevenueByRowTypes()
			),
			'total_margin' => $this->getParameters(
				$helper->getTotalMarginByRowTypes()
			),
			'total_percentage_margin' => $this->getParameters(
				$helper->getTotalPercentageMarginByRowTypes()
			)
		];

		$this->containerModel->fieldsToUpdateOnTableEdit = array_merge(
			$this->containerModel->fieldsToUpdateOnTableEdit,
			array_keys($fields)
		);

		$fields['total_percentage_margin']['widthClass'] = 'uk-width-2-5';

		return [
			'translationPrefix' => 'products::fields',
			'canBeHidden' => false,
			'fields' => $fields,
			'width' => ['medium']
		];
	}

	public function getHorizontalFormFieldsetParameters() : array
	{
		$helper = RowContainerCalculationsHelper::create($this->containerModel);

		$horizontalParameters = [
			'widthClass' => 'uk-width-1-4',
			'showLabel' => true
		];

		$fields = [
			'horizontal_total_cost' => $this->getParameters(
				$this->containerModel->horizontal_total_cost, $horizontalParameters
			),
			'horizontal_total_revenue' => $this->getParameters(
				$helper->getTotalRevenueByRowTypes(), $horizontalParameters
			),
			'horizontal_total_margin' => $this->getParameters(
				$helper->getTotalMarginByRowTypes(), $horizontalParameters
			),
			'horizontal_total_percentage_margin' => $this->getParameters(
				$helper->getTotalPercentageMarginByRowTypes(), $horizontalParameters
			)
		];

		$this->containerModel->fieldsToUpdateOnTableEdit = array_merge(
			$this->containerModel->fieldsToUpdateOnTableEdit,
			array_keys($fields)
		);

		return [
			'translationPrefix' => 'products::fields',
			'canBeHidden' => false,
			'fields' => $fields,
			'width' => ['1-1']
		];
	}}

