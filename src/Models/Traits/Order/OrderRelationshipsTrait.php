<?php

namespace IlBronza\Products\Models\Traits\Order;

use IlBronza\Clients\Models\Client;
use IlBronza\Clients\Models\Destination;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Product\Product;
use Illuminate\Support\Collection;

trait OrderRelationshipsTrait
{
	public function products()
	{
        return $this->belongsToMany(
            Product::getProjectClassName(),
            config('products.models.orderProduct.table')
        );
        // ->wherePivot('deleted_at', '!=', 'null');
        // ->where(function($query)
        // {
        //     $query->whereNull(
        //         config('products.models.orderProduct.table') . '.deleted_at'
        //     );
        // });
	}

    public function getProducts() : ? Collection
    {
        return $this->products;
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::getProjectClassName());
    }

    public function getOrderProducts() : Collection
    {
    	return $this->orderProducts;
    }

    public function orderProductPhases()
    {
        return $this->hasManyThrough(
            OrderProductPhase::getProjectClassName(),
            OrderProduct::getProjectClassName()
        );
    }

    public function getOrderProductPhases() : ? Collection
    {
        return $this->orderProductPhases;
    }

    public function destination()
    {
        return $this->belongsTo(Destination::getProjectClassName());
    }

    public function getDestination() : ? Destination
    {
        if(! $this->destination_id)
            return $this->getClient()?->getDefaultDestination();

        if($this->relationLoaded('destination'))
            return $this->destination;

        return Destination::getProjectClassName()::findCached($this->destination_id);
    }

    public function provideDestination() : ? Destination
    {
        if($destination = $this->getDestination())
            return $destination;

        return $this->getClient()?->getDestination();
    }

    public function client()
    {
        return $this->belongsTo(Client::getProjectClassName());
    }

    public function getClient() : ? Client
    {
        if(! $this->client_id)
            return null;

        if($this->relationLoaded('client'))
            return $this->client;

        return Client::getProjectClassName()::findCached($this->client_id);
    }

}