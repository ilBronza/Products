<?php

namespace IlBronza\Products\Models\Traits\Order;

use IlBronza\Clients\Models\Client;
use IlBronza\Clients\Models\Destination;
use IlBronza\Clients\Models\Traits\InteractsWithClientsTrait;
use IlBronza\Clients\Models\Traits\InteractsWithDestinationTrait;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Warehouse\Models\Pallettype\Pallettype;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

trait OrderRelationshipsTrait
{
    use InteractsWithClientsTrait;
    use InteractsWithDestinationTrait;

    public function pallettype()
    {
        return $this->belongsTo(Pallettype::getProjectClassName());
    }

    public function getPallettype() : ? Pallettype
    {
        return $this->pallettype;
    }

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

    public function getDestination() : ? Destination
    {
        if(! $this->destination_id)
        {
            Log::critical('risolvere questa cosa con un destino default generico e non per cliente');
            return $this->getClient()?->getDefaultDestination();
        }

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

}