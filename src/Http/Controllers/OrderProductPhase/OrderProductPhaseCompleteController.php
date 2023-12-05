<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCRUD;

class OrderProductPhaseCompleteController extends OrderProductPhaseCRUD
{
    public $allowedMethods = ['complete'];

    public function complete($orderProductPhase)
    {
        $orderProductPhase = $this->findModel($orderProductPhase);

        $orderProductPhase->forceCompletion();

        return back();
    }
}
