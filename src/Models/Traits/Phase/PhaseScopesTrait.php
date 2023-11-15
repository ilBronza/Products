<?php

namespace IlBronza\Products\Models\Traits\Phase;

use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Product\Product;

trait PhaseScopesTrait
{
    public function scopeExcludingWorkstationId($query, string $id)
    {
        $query->where('workstation_id', '!=', $id);
    }

    public function scopeExcludingWorkstationIds($query, array $ids)
    {
        $query->whereNotIn('workstation_id', $ids);
    }

    public function scopeByWorkstationsIds($query, array|Collection $ids)
    {
        $query->whereIn('workstation_id', $ids);
    }

    public function scopeByWorkstationsId($query, string $id)
    {
        $query->where('workstation_id', $id);
    }
}