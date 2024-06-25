<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Sellables\SellableSupplier;

trait OrderrowRelationsScopesTrait
{
    public function sellableSupplier()
    {
        return $this->belongsTo(SellableSupplier::getProjectClassname());
    }

    public function order()
    {
    	return $this->belongsTo(Order::getProjectClassname());
    }
}