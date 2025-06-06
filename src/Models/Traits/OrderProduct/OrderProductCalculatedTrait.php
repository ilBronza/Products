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
}