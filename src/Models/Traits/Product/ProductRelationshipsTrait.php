<?php

namespace IlBronza\Products\Models\Traits\Product;

use App\Models\ProductsPackage\Size;
use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\Accessory;
use IlBronza\Products\Models\AccessoryProduct;
use IlBronza\Products\Models\Finishing;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Packing;
use IlBronza\Products\Models\Phase;
use IlBronza\Warehouse\Models\Pallettype\Pallettype;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait ProductRelationshipsTrait
{
	public function finishing()
	{
		return $this->belongsTo(Finishing::gpc());
	}

	public function pallettype()
	{
		return $this->belongsTo(Pallettype::gpc());
	}

	public function getPallettype() : Pallettype
	{
		if($pallettype = $this->getPacking()?->getPallettype())
			return $pallettype;

		if($pallettype = $this->getClient()?->getPallettype())
			return $pallettype;

		return Pallettype::gpc()::getDefault();
	}

	public function size()
	{
		return $this->belongsTo(Size::class);
	}

	public function getSize() : Size
	{
		return $this->provideSizeModelForExtraFields();
	}

	public function provideSizeModelForExtraFields() : Size
	{
		if(! $this->size)
		{
			$size = Size::create();

			$size->sizeable_type = static::gpc();
			$size->sizeable_id = $this->getKey();

			$this->size()->associate($size);
			$this->save();

			return $this->size;
		}

		return $this->size;
	}

	public function packing()
    {
        return $this->belongsTo(Packing::class);
    }

    public function getPacking() : Packing
    {
    	if($this->packing)
    		return $this->packing;

    	$this->providePackingModelForExtraFields();

    	return $this->packing;
    }

	public function providePackingModelForExtraFields() : Packing
	{
		if(! $this->packing)
		{
			$packing = Packing::create();

			$packing->packable_type = static::gpc();
			$packing->packable_id = $this->getKey();

			$this->packing()->associate($packing);
			$this->save();

			return $this->packing;
		}

		return $this->packing;
	}

	public function accessories()
	{
		return $this->belongsToMany(
			Accessory::gpc(), config('products.models.accessoryProduct.table')
		)->using(
			AccessoryProduct::gpc()
		);
	}

	public function accessoryProducts()
	{
		return $this->hasMany(AccessoryProduct::gpc());
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
		return $this->belongsTo(Client::gpc());
	}

	public function getClient() : Client
	{
		return $this->getOrFindCachedRelatedElement('client');
	}

	public function phases()
	{
		return $this->hasMany(Phase::gpc())->orderBy('sequence');
	}

	public function getPhases() : Collection
	{
		return $this->phases;
	}

	public function orderProducts()
	{
		return $this->hasMany(OrderProduct::gpc());
	}

	public function getOrderProducts() : Collection
	{
		return $this->orderProducts;
	}

	public function lastOrderProduct()
	{
		return $this->belongsTo(OrderProduct::gpc(), 'live_last_order_product_id', 'id');
	}

	public function orderProductPhases()
	{
        return $this->hasManyThrough(
            OrderProductPhase::gpc(),
            OrderProduct::gpc()
        );		
	}

	public function getOrderProductPhases() : Collection
	{
		return $this->orderProductPhases;
	}

	public function orders()
	{
		return $this->belongsToMany(Order::gpc(), config('products.models.orderProduct.table'));
	}

	public function getOrders() : Collection
	{
		return $this->orders;
	}

	public function activeOrders()
	{
		return $this->orders()->active();
	}

	public function getLastOrderProduct()
	{
		return $this->orderProducts()->orderBy('created_at', 'DESC')->first();
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
				// Log::warning('If you see this log too often, consider querying relation for ' . Str::snake($relationName) . '_count field');
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