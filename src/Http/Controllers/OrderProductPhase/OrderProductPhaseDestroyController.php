<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class OrderProductPhaseDestroyController extends OrderProductPhaseCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($orderProductPhase)
    {
        $orderProductPhase = $this->findModel($orderProductPhase);

        return $this->_destroy($orderProductPhase);
    }

}
