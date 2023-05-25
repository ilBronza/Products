<?php

namespace IlBronza\Products\Models\Traits\Phase;

use IlBronza\Products\Models\Product;

trait PhaseRelationshipsTrait
{
	public function product()
	{
		return $this->belongsTo(Product::getProjectClassName());
	}

}