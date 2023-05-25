<?php

namespace IlBronza\Products\Models\Traits\Product;

use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\Phase;

trait ProductRelationshipsTrait
{
	public function client()
	{
		return $this->belongsTo(Client::getProjectClassName());
	}

	public function phases()
	{
		return $this->hasMany(Phase::getProjectClassName());
	}

	public function orderProducts()
	{
		return $this->hasMany(OrderProduct::getProjectClassName());
	}

	public function orders()
	{
		return $this->belongsToMany(Order::getProjectClassName(), config('products.models.orderProduct.table'));
	}

}