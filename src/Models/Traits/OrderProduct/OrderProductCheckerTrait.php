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
}