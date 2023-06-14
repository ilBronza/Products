<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;
use IlBronza\Products\Http\Controllers\Order\OrderCRUD;

class OrderShowController extends OrderCRUD
{
    use CRUDProductPackageShowTrait;
    public $allowedMethods = ['show'];

    public function show($order)
    {
        $order = $this->findModel($order);

        return $this->_show($order);
    }
}
