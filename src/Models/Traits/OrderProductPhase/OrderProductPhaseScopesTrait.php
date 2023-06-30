<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\Product\Product;
use Illuminate\Support\Collection;

trait OrderProductPhaseScopesTrait
{
    public function scopeByQuantityDoneInterval($query, float $fromQuantity, float $toQuantity)
    {
        $query
            ->where('quantity_done', '>', $fromQuantity)
            ->where('quantity_done', '<', $toQuantity);
    }

    public function scopeByProductsIds($query, array|Collection $ids)
    {
        $query->whereHas('orderProduct', function($_query) use($ids)
            {
                $_query->whereIn('product_id', $ids);
            });
    }

    public function scopeByOrdersIds($query, array|Collection $ids)
    {
        $query->whereHas('orderProduct', function($_query) use($ids)
            {
                $_query->whereIn('order_id', $ids);
            });
    }

    public function scopeSortByExtraField($query, string $field, $type = 'ASC')
    {
        $query->whereHas('extraFields', function($_query) use($field, $type)
            {
                $_query->orderBy($field, $type);
            });
    }

    public function scopeByWorkstationsIds($query, array|Collection $ids)
    {
        $query->whereHas('phase', function($_query) use($ids)
        {
            $_query->whereIn('workstation_id', $ids);
        })
        ->orWhereIn('workstation_overridden_id', $ids);
    }

    public function scopeByWorkstationId($query, string $id)
    {
        $query->whereHas('phase', function($_query) use($id)
        {
            $_query->where('workstation_id', $id);
        })
        ->orWhere('workstation_overridden_id', $id);
    }

    public function scopeWithClientId($query)
    {
        $orderProductPhasePlaceholder = OrderProduct::getProjectClassName()::make();

        $query->addSelect([
            'live_client_id' => Product::getProjectClassName()::select('client_id')
                    ->join(
                        $orderProductPhasePlaceholder->getTable(),
                        $orderProductPhasePlaceholder->getTable() . '.product_id',
                        '=',
                        Product::getProjectClassName()::make()->getTable() . '.id'
                    )
                    ->whereColumn('products__order_products.id', $this->getTable() . '.order_product_id')
                    ->take(1)
        ]);
    }

    public function scopeWithPrevious($query)
    {
        $orderProductPhasePlaceholder = static::make();

        $query->addSelect([
            'live_previous_id' => static::select('id')
                    ->whereColumn('id', $this->getTable() . '.id')
                    ->take(1)
        ]);
    }

    public function scopeWithOrderId($query)
    {
        $query->addSelect([
            'live_order_id' => OrderProduct::getProjectClassName()::select('order_id')
                    ->whereColumn('products__order_products.id', $this->getTable() . '.order_product_id')
                    ->take(1)
        ]);
    }

    public function scopeWithClient($query)
    {
        $query->withClientId()->with('client');
    }
}