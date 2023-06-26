<?php

namespace IlBronza\Products\Models\Traits\OrderProduct;

use IlBronza\Products\Models\Product\Product;

trait OrderProductScopesTrait
{
    public function scopeByWorkstationId($query, string $workstationId)
    {
        $query->whereHas('phases', function($_query) use($workstationId)
        {
            $_query->where('workstation_id', $workstationId);
        })->orWhereHas('orderProductPhases', function($_query) use($workstationId)
        {
            $_query->where('workstation_overridden_id', $workstationId);
        });
    }

    public function scopeWithProductName($query)
    {
        $query->addSelect([
            'live_product_name' => Product::getProjectClassName()::select('name')
                    ->whereColumn('product_id', 'products__products.id')
                    ->take(1)
        ]);
    }

    public function scopeWithProductShortDescription($query)
    {
        $query->addSelect([
            'live_product_short_description' => Product::getProjectClassName()::select('short_description')
                    ->whereColumn('product_id', 'products__products.id')
                    ->take(1)
        ]);
    }



    public function scopeWithClientId($query)
    {
        // $orderProductPhasePlaceholder = OrderProduct::getProjectClassName()::make();

        $query->addSelect([
            'live_client_id' => Product::getProjectClassName()::select('client_id')
            		->whereColumn('product_id', 'products__products.id')
                    // ->whereColumn('products__order_products.id', $this->getTable() . '.order_product_id')
                    ->take(1)
        ]);
    }
}