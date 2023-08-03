<?php

namespace IlBronza\Products\Models\Traits\OrderProduct;

use Carbon\Carbon;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Product\Product;

trait OrderProductScopesTrait
{
    public function scopeWithLastOrderProductPhaseId($query)
    {
        $query->addSelect([
            'live_last_order_product_phase_id' => OrderProductPhase::getProjectClassName()::select('id')
                    ->whereColumn('products__order_product_phases.order_product_id', $this->getTable() . '.id')
                    ->orderBy('sequence', 'DESC')
                    ->take(1)
        ]);
    }

    public function scopeWithFirstOrderProductPhaseId($query)
    {
        $query->addSelect([
            'live_first_order_product_phase_id' => OrderProductPhase::getProjectClassName()::select('id')
                    ->whereColumn('products__order_product_phases.order_product_id', $this->getTable() . '.id')
                    ->orderBy('sequence', 'ASC')
                    ->take(1)
        ]);
    }

    public function scopeWithLastOrderProductPhase($query)
    {
        $query->withLastOrderProductPhaseId()->with('lastOrderProductPhase');
    }

    public function scopeWithOrderProductPhasesCompletedDate($query, Carbon $date)
    {
        $query->whereHas('orderProductPhases', function($_query) use($date)
        {
            $_query->completedDate($date);
        });        
    }

    public function scopeAvoidingWorkstationIds($query, array $workstationsIds)
    {
        $query->whereHas('phases', function($_query) use($workstationsIds)
        {
            $_query->whereNotIn('workstation_id', $workstationsIds);
        })->orWhereHas('orderProductPhases', function($_query) use($workstationsIds)
        {
            $_query->whereNotIn('workstation_overridden_id', $workstationsIds);
        });
    }

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

    public function scopeWithProductStencilId($query)
    {
        $query->addSelect([
            'live_stencil_id' => Product::getProjectClassName()::select('stencil_id')
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