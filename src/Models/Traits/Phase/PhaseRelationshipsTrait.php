<?php

namespace IlBronza\Products\Models\Traits\Phase;

use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Product\Product;
use Illuminate\Support\Collection;

trait PhaseRelationshipsTrait
{
	public function client()
	{
		return $this->hasOneThrough(
			Client::getProjectClassName(),
			Product::getProjectClassName(),
			'id', // refers to id column on product table
			'id', // refers to id column on client table
			'product_id',
			'client_id' // refers to client_id column on products table
		);
	}

	public function getProduct() : ? Product
	{
		return $this->product;
	}

	public function product()
	{
		return $this->belongsTo(Product::getProjectClassName());
	}

	public function getOrderProductPhases() : Collection
	{
		return $this->orderProductPhases;
	}

	public function orderProductPhases()
	{
		return $this->hasMany(OrderProductPhase::getProjectClassName());
	}

}