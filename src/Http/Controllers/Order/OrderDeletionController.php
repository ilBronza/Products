<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class OrderDeletionController extends OrderCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($order)
    {
        $order = $this->findModel($order);

        return $this->_destroy($order);
    }
}
