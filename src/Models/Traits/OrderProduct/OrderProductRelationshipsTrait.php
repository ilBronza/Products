<?php

namespace IlBronza\Products\Models\Traits\OrderProduct;

use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Product;

trait OrderProductRelationshipsTrait
{
	public function product()
	{
		return $this->belongsTo(Product::getProjectClassName());
	}

	public function order()
	{
		return $this->belongsTo(Order::getProjectClassName());
	}

}