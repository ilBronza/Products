<?php

namespace IlBronza\Products\Providers\Helpers\CostCalculations;

use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsCostsFieldsHelper;

class RowContainerCalculationsHelper
{
	public function __construct(ProductPackageBaseRowcontainerModel $rowContainer, array $rowRelations)
	{
		$this->rowContainer = $rowContainer;
		$this->rowRelations = $rowRelations;
	}

	static function create(ProductPackageBaseRowcontainerModel $rowContainer, array $rowRelations)
	{
		return new static($rowContainer, $rowRelations);
	}

	public function getTotalRevenueByRowTypes()
	{
		return $this->rowContainer->getTotalRevenue();
	}

	public function getTotalCostByRowTypes()
	{
		return $this->rowContainer->getTotalCost();
	}

	public function getTotalMarginByRowTypes()
	{
		return $this->rowContainer->getTotalMargin();
	}

	public function getTotalPercentageMarginByRowTypes()
	{
		return $this->rowContainer->getTotalPercentageMargin();
	}
}