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
	public float $totalGains = 0;
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

	public function calculateTotals()
	{
		foreach ($this->getRows() as $row)
		{
			$this->totalCosts += $row->total_costs ?? 0;
			$this->totalGains += $row->total_gains ?? 0;
		}

		$this->margin = $this->totalGains - $this->totalCosts;

		if ($this->totalGains != 0)
			$this->marginPercentage = ($this->margin / $this->totalGains) * 100;
	}

	public function getFormFieldsetParameters() : array
	{
		$this->calculateTotals();

		return [
			'translationPrefix' => 'products::fields',
			'fields' => [
				$this->fieldPrefix . '_total_costs' => $this->getParameters($this->totalCosts),
				$this->fieldPrefix . '_total_gains' => $this->getParameters($this->totalGains),
				$this->fieldPrefix . '_margin' => $this->getParameters($this->margin),
				$this->fieldPrefix . '_margin_percentage' => $this->getParameters($this->marginPercentage, [
					'widthClass' => 'uk-width-2-5'
				])
			],
			'width' => ['small']
		];
	}

	public function getRows() : Collection
	{
		return $this->containerModel->{$this->rowsRelationName};
	}
}

