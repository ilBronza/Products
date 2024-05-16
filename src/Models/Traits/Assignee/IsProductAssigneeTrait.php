<?php

namespace IlBronza\Products\Models\Traits\Assignee;

use IlBronza\Products\Models\OrderProductPhase;

trait IsProductAssigneeTrait
{
    public function assignedOrderProductPhases()
    {
        return $this->belongsToMany(
            OrderProductPhase::getProjectClassName(),
            config('products.models.assigneeTarget.table'),
            'user_id',
            'target_id'
        )->where('type', 'opp');
    }

    public function assignedOrderProducts()
    {
        return $this->belongsToMany(
            OrderProductPhase::getProjectClassName(),
            config('products.models.assigneeTarget.table'),
            'user_id',
            'target_id'
        )->where('type', 'op');
    }

    public function assignedOrders()
    {
        return $this->belongsToMany(
            OrderProductPhase::getProjectClassName(),
            config('products.models.assigneeTarget.table'),
            'user_id',
            'target_id'
        )->where('type', 'o');
    }
}