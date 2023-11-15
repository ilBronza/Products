<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCRUD;

class OrderProductPhaseTerminateController extends OrderProductPhaseCRUD
{
    public $allowedMethods = ['terminate'];

    public function terminate($orderProductPhase)
    {
        $orderProductPhase = $this->findModel($orderProductPhase);

        $orderProductPhase->terminate();

        return back();
    }
}
