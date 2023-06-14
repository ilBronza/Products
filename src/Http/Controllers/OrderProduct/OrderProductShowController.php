<?php

namespace IlBronza\Products\Http\Controllers\OrderProduct;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductCRUD;

class OrderProductShowController extends OrderProductCRUD
{
    use CRUDProductPackageShowTrait;
    public $allowedMethods = ['show'];

    public function show($orderProduct)
    {
        $orderProduct = $this->findModel($orderProduct);

        return $this->_show($orderProduct);
    }
}
