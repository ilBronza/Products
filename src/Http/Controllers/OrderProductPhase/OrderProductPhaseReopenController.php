<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCRUD;

class OrderProductPhaseReopenController extends OrderProductPhaseCRUD
{
    public $allowedMethods = ['reopen', 'reopenMachineInitialization'];

    public function reopenMachineInitialization($orderProductPhase)
    {
        $orderProductPhase = $this->findModel($orderProductPhase);

        foreach($processings = $orderProductPhase->processings()->machineInitializationCompleted()->get() as $processing)
            $processing->setPressedButton('to_complete', true);

        return back();
    }

    public function reopen($orderProductPhase)
    {
        $orderProductPhase = $this->findModel($orderProductPhase);

        foreach($processings = $orderProductPhase->processings()->definitive()->get() as $processing)
            $processing->setAsNonDefinitive();

        return back();
    }
}
