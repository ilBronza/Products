<?php

namespace IlBronza\Products\Http\Controllers\OrderProduct;

use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductCRUD;

class OrderProductRestoreController extends OrderProductCRUD
{
    public $allowedMethods = ['restore'];

    public function restore($orderProduct)
    {
        $orderProduct = $this->findModelWithTrashed($orderProduct);

        $orderProduct->restoreWithRelated();

        return back();
    }
}
