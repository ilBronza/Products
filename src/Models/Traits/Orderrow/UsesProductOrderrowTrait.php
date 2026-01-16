<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

use IlBronza\Products\Models\Orderrows\ProductOrderrow;
use IlBronza\Products\Models\ProductPackageBaseRowcontainerModel;

trait UsesProductOrderrowTrait
{
	public function productOrderrows()
	{
		return $this->hasMany(ProductOrderrow::gpc());
	}

	public function getAddProductUrl() : string
	{
		return $this->getAddRowByTypeUrl('Product');
	}

	public function getTotalProductsCostAttribute() : float
	{
		return round($this->productOrderrows->sum('total_cost'), 2);
	}

	public function getTotalProductsPriceAttribute() : float
	{
		return round($this->productOrderrows->sum('total_price'), 2);
	}



}