<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

use IlBronza\Products\Models\Order;

trait OrderrowRelationsScopesTrait
{
	public function modelContainer()
	{
		return $this->order();
	}

	public function order()
	{
		return $this->belongsTo(Order::gpc());
	}
}