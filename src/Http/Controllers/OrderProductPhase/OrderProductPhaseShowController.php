<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCRUD;

class OrderProductPhaseShowController extends OrderProductPhaseCRUD
{
    use CRUDProductPackageShowTrait;
    public $allowedMethods = ['show'];

    public function show($orderProductPhase)
    {
        $orderProductPhase = $this->findModel($orderProductPhase);

        return $this->_show($orderProductPhase);
    }
}
