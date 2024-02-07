<?php

namespace IlBronza\Products\Models\Traits\OrderProduct;

use IlBronza\Products\Models\Product\Product;

trait OrderProductCheckerTrait
{
    public function isSingleWorkstation(string $workstationId) : bool
    {
        foreach($this->getOrderProductPhases() as $orderProductPhase)
            if($orderProductPhase->getWorkstationId() != $workstationId)
                return false;

        return true;
    }

    public function hasBeenPartialized() : bool
    {
        foreach($this->getOrderProductPhases() as $orderProductPhase)
            if($orderProductPhase->isWorkstation99())
                return true;

        return false;
    }

    public function hasEnoughQuantityDone() : ? bool
    {
        if($this->hasBeenPartialized())
            return null;

        return $this->getQuantityDoneDiscrepancy() >= 0;
    }
}