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
	public array $rowsTypes;

	public function __construct(ProductPackageBaseRowcontainerModel $containerModel, array $rowsTypes)
	{
		$this->containerModel = $containerModel;
		$this->rowsTypes = $rowsTypes;

		// $this->fieldPrefix = Str::snake($rowsRelationName);
	}

	/**
	 * Esempio:
	 * RowsCostsFieldsHelper::getFormFieldsetsByRowsType($containerModel, 'operatorRows')
	 */
	static function getFormFieldsetsByRowsTypes(ProductPackageBaseRowcontainerModel $containerModel, array $rowsTypes) : array
	{
		$helper = new static($containerModel, $rowsTypes);

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

	public function getFormFieldsetParameters() : array
	{
		$helper = RowContainerCalculationsHelper::create($this->containerModel, $this->rowsTypes);

		$fields = [
			'total_revenue' => $this->getParameters(
				$helper->getTotalRevenueByRowTypes()
			),
			'total_cost' => $this->getParameters(
				$helper->getTotalCostByRowTypes()
			),
			'total_margin' => $this->getParameters(
				$helper->getTotalMarginByRowTypes()
			),
			'total_margin_percentage' => $this->getParameters(
				$helper->getTotalPercentageMarginByRowTypes()
			)
		];

		$fields['total_margin_percentage']['widthClass'] = 'uk-width-2-5';

		return [
			'translationPrefix' => 'products::fields',
			'canBeHidden' => false,
			'fields' => $fields,
			'width' => ['small']
		];
	}
}

