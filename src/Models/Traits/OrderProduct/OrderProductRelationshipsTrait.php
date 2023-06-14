<?php

namespace IlBronza\Products\Models\Traits\OrderProduct;

use IlBronza\Clients\Models\Client;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Product;
use IlBronza\Ukn\Facades\Ukn;
use Illuminate\Support\Collection;

trait OrderProductRelationshipsTrait
{
	public function product()
	{
		return $this->belongsTo(Product::getProjectClassName());
	}

    public function getProduct() : ? Product
    {
    	if($product = $this->getOrFindCachedRelatedElement('product'))
    		return $product;

    	Ukn::e('Manca il prodotto per orderProduct <a href="' . $this->getEditUrl() . '">'  . $this->getKey() . '</a>');

        return null;
    }

	public function order()
	{
		return $this->belongsTo(Order::getProjectClassName());
	}

	public function getOrder() : ? Order
	{
		if($this->relationLoaded('order'))
			return $this->order;

		return Order::getProjectClassName()::findCached($this->order_id);
	}

	public function orderProductPhases()
	{
		return $this->hasMany(OrderProductPhase::getProjectClassName());
	}

	public function getOrderProductPhases() : Collection
	{
		return $this->orderProductPhases;
	}

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

	public function getClient()
	{
		return $this->getOrFindCachedRelation('client');
	}
}