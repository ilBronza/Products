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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

	public function activeOrders()
	{
		return $this->orders()->active();
	}

	public function getCountRelation(string $relationName, int $value = null)
	{
		if(! is_null($value))
			return $value;

		if($this->relationLoaded($relationName))
			return $this->{$relationName}->count();

		return cache()->remember(
			$this->cacheKey(Str::snake($relationName) . "_count"),
			3600,
			function() use($relationName)
			{
				Log::warning('If you see this log too often, consider querying relation for ' . Str::snake($relationName) . '_count field');
				return $this->{$relationName}()->count();
			}
		);
	}

	public function getActiveOrdersCountAttribute($value)
	{
		return $this->getCountRelation('activeOrders', $value);
	}

	public function getOrdersCountAttribute($value)
	{
		return $this->getCountRelation('orders', $value);
	}
}