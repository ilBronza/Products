<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

use Illuminate\Support\Collection;

trait OrderProductPhaseScopesTrait
{
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

                
}