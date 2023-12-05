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
        $query->where(function($_query) use($ids)
        {
            $_query->whereHas('phase', function($__query) use($ids)
            {
                $__query->whereIn('workstation_id', $ids);
            })->whereNull('workstation_overridden_id');
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

    public function scopeExcludingWorkstationId($query, string $id)
    {
        $query->whereHas('phase', function($_query) use($id)
        {
            $_query->where('workstation_id', '!=', $id);
        })
        ->where('workstation_overridden_id', '!=', $id);
    }

    public function scopeExcludingWorkstationIds($query, array $ids)
    {
        $query->whereHas('phase', function($_query) use($ids)
        {
            $_query->whereNotIn('workstation_id', $ids);
        })
        ->whereNotIn('workstation_overridden_id', $ids);
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
                    ->fromRaw($this->getTable() . ' oop')
                    ->whereColumn('order_product_id', $this->getTable() . '.order_product_id')
                    ->where('sequence', \DB::raw('(' . $this->getTable() . '.sequence - 1)'))
                    ->take(1)
        ])->with('previous');
    }

    public function scopeWithNext($query)
    {
        $orderProductPhasePlaceholder = static::make();

        $query->addSelect([
            'live_next_id' => static::select('id')
                    ->fromRaw($this->getTable() . ' oop')
                    ->whereColumn('order_product_id', $this->getTable() . '.order_product_id')
                    ->where('sequence', \DB::raw('(' . $this->getTable() . '.sequence + 1)'))
                    ->take(1)
        ])->with('next');
    }

    public function scopeBySequence($query, string $type = 'ASC')
    {
        return $query->orderBy('sequence', $type);
    }

    public function scopeWithOrderId($query)
    {
        $query->addSelect([
            'live_order_id' => OrderProduct::getProjectClassName()::select('order_id')
                    ->whereColumn('products__order_products.id', $this->getTable() . '.order_product_id')
                    ->take(1)
        ]);
    }

    public function scopeWithProductId($query)
    {
        $query->addSelect([
            'live_product_id' => OrderProduct::getProjectClassName()::select('product_id')
                    ->whereColumn('products__order_products.id', $this->getTable() . '.order_product_id')
                    ->take(1)
        ]);
    }

    public function scopeWithClient($query)
    {
        $query->withClientId()->with('client');
    }
}