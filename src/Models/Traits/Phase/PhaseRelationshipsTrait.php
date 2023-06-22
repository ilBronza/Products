<?php

namespace IlBronza\Products\Models\Traits\Phase;

use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Product\Product;

trait PhaseRelationshipsTrait
{
	public function product()
	{
		return $this->belongsTo(Product::getProjectClassName());
	}

	public function orderProductPhases()
	{
		return $this->hasMany(OrderProductPhase::getProjectClassName());
	}

}