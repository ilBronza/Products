<?php

namespace IlBronza\Products\Models\Traits\Product;

use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\Accessory;
use IlBronza\Products\Models\AccessoryProduct;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Phase;
use Illuminate\Support\Collection;

trait ProductRelationshipsTrait
{
	public function accessories()
	{
		return $this->belongsToMany(
			Accessory::getProjectClassName(), config('products.models.accessoryProduct.table')
		)->using(
			AccessoryProduct::getProjectClassName()
		);
	}

	public function accessoryProducts()
	{
		return $this->hasMany(AccessoryProduct::getProjectClassName());
	}

	public function products()
	{
		return $this->descendants();
	}

	public function productRelations()
	{
		return $this->descendantPivots();
	}

	public function client()
	{
		return $this->belongsTo(Client::getProjectClassName());
	}

	public function phases()
	{
		return $this->hasMany(Phase::getProjectClassName())->orderBy('sequence');
	}

	public function getPhases() : Collection
	{
		return $this->phases;
	}

	public function orderProducts()
	{
		return $this->hasMany(OrderProduct::getProjectClassName());
	}

	public function orderProductPhases()
	{
        return $this->hasManyThrough(
            OrderProductPhase::getProjectClassName(),
            OrderProduct::getProjectClassName()
        );		
	}

	public function orders()
	{
		return $this->belongsToMany(Order::getProjectClassName(), config('products.models.orderProduct.table'));
	}

}