<?php

namespace IlBronza\Products\Models\Traits\OrderProduct;

trait OrderProductCalculatedTrait
{
	public function getPiecesPerPacking() : ?float
	{
		return $this->getProduct()->getQuantityPerUnitload();
	}

	public function getPackageNumber() : ?int
	{
		if ((! $pieces = $this->getQuantityDone()) && (! $pieces = $this->getQuantityRequired()))
			return null;

		if (! $piecesPerPacking = $this->getPiecesPerPacking())
			return null;

		return ceil($pieces / $piecesPerPacking);
	}

	public function getCoefficientOutput() : ?float
	{
		return cache()->remember(
			$this->cacheKey('getCoefficientOutput'),
			3600 * 24,
			function()
			{
				$result = 1;
				
				foreach($this->getOrderProductPhases() as $orderProductPhase)
					$result = $result * $orderProductPhase->getCoefficientOutput();

				return $result;				
			}
		);
	}

}