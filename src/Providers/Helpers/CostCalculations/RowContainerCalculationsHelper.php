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
		$totalRevenue = 0;

		foreach($this->rowRelations as $rowRelation)
		{
			$fieldName = RowsCostsFieldsHelper::getRevenueFieldName($rowRelation);

			$totalRevenue += $this->rowContainer->$fieldName;
		}

		return $totalRevenue;
	}

	public function getTotalCostByRowTypes()
	{
		$totalRevenue = 0;

		foreach($this->rowRelations as $rowRelation)
		{
			$fieldName = RowsCostsFieldsHelper::getCostFieldName($rowRelation);

			$totalRevenue += $this->rowContainer->$fieldName;
		}

		return $totalRevenue;
	}

	public function getTotalMarginByRowTypes()
	{
		return $this->getTotalRevenueByRowTypes() - $this->getTotalCostByRowTypes();
	}

	public function getTotalPercentageMarginByRowTypes()
	{
		if(! $revenue = $this->getTotalRevenueByRowTypes())
			return 0;

		return round($this->getTotalMarginByRowTypes() / $revenue * 100, 2);		
	}
}