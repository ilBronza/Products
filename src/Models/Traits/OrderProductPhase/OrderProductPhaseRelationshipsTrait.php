<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

use App\Workstation;
use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\Product;

trait OrderProductPhaseRelationshipsTrait
{
	public function client()
	{
		dd(__METHOD__);
		// return $this->hasOne(Client::getProjectClassName(), 'id', 'client_id');
	}

    public function workstation()
    {
        return $this->belongsTo(
        	Workstation::class,
        	'workstation_overridden_id'
        );
    }

	public function orderProduct()
	{
		return $this->belongsTo(OrderProduct::getProjectClassName());
	}

	public function getOrderProduct() : ? OrderProduct
	{
		return $this->orderProduct;
	}

	public function phase()
	{
		return $this->belongsTo(Phase::getProjectClassName());
	}

	public function getPhase() : ? Phase
	{
		return $this->phase;
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
		return $this->order;
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
		return $this->product;
	}

}