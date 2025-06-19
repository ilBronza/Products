<?php

namespace IlBronza\Products\Models\Traits\Order;

use IlBronza\Clients\Models\Destination;
use IlBronza\Products\Models\Finishing;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Warehouse\Models\Pallettype\Pallettype;
use Illuminate\Support\Collection;

trait OrderRelationshipsTrait
{

    public function pallettype()
    {
        return $this->belongsTo(Pallettype::gpc());
    }

    public function getPallettype() : ? Pallettype
    {
        return $this->pallettype;
    }

	public function products()
	{
        return $this->belongsToMany(
            Product::gpc(),
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
        return $this->hasMany(OrderProduct::gpc());
    }

    public function getOrderProducts() : Collection
    {
    	return $this->orderProducts;
    }

    public function orderProductPhases()
    {
        return $this->hasManyThrough(
            OrderProductPhase::gpc(),
            OrderProduct::gpc()
        );
    }

    public function getOrderProductPhases() : ? Collection
    {
        return $this->orderProductPhases;
    }

    public function provideDestination() : ? Destination
    {
        if($destination = $this->getDestination())
            return $destination;

        return $this->getClient()?->getDestination();
    }

	public function getQuotation()
	{
		return $this->quotation;
	}

	public function quotation()
	{
		return $this->belongsTo(Quotation::gpc());
	}
}