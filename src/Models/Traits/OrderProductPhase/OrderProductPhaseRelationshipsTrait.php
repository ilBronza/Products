<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

use App\Workstation;
use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\Product\Product;
use Illuminate\Support\Collection;

trait OrderProductPhaseRelationshipsTrait
{
	public function client()
	{
		return $this->belongsTo(Client::getProjectClassName(), 'live_client_id', 'id');
	}

    public function workstation()
    {
        return $this->belongsTo(
        	Workstation::class,
        	'workstation_overridden_id'
        );
    }

	public function getWorkstation()
	{
		if($this->relationLoaded('workstation'))
			return $this->workstation;

		return Workstation::findCached(
			$this->getWorkstationId()
		);
	}

	public function orderProduct()
	{
		return $this->belongsTo(OrderProduct::getProjectClassName());
	}

	public function getOrderProduct() : ? OrderProduct
	{
		return $this->getOrFindCachedRelation('orderProduct');
	}

	public function phase()
	{
		return $this->belongsTo(Phase::getProjectClassName());
	}

	public function getPhase() : ? Phase
	{
		return $this->getOrFindCachedRelation('phase');
	}

	public function order()
	{
		return $this->hasOneThrough(
            Order::getProjectClassName(),
            OrderProduct::getProjectClassName(),
            'id', // refers to id column on order_products table
            'id', // refers to id column on orders table
            'order_product_id',
            'order_id' // refers to order_id column on order_products table
        );
	}

	public function getOrder() : ? Order
	{
		return $this->getOrFindCachedRelation('order');
	}

	public function product()
	{
		return $this->hasOneThrough(
            Product::getProjectClassName(),
            OrderProduct::getProjectClassName(),
            'id', // refers to id column on order_products table
            'id', // refers to id column on orders table
            'order_product_id',
            'product_id' // refers to order_id column on order_products table
        );
	}

	public function getProduct() : ? Product
	{
		return $this->getOrFindCachedRelation('product');
	}

	public function orderProductPhases()
	{
		return $this->hasMany(static::getProjectClassName(), 'order_product_id', 'order_product_id');
	}

	public function getOrderProductPhases() : Collection
	{
		return $this->getOrFindCachedRelation('orderProductPhases');
	}
}