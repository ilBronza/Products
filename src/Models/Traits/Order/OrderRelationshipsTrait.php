<?php

namespace IlBronza\Products\Models\Traits\Order;

use IlBronza\Products\Models\Product;

trait OrderRelationshipsTrait
{
	public function product()
	{
		return $this->belongsTo(Product::getProjectClassName());
	}

}