<?php

namespace IlBronza\Products\Http\Controllers\Order;

class OrderDeletionController extends OrderCRUD
{
    public $allowedMethods = ['destroy'];

    public function destroy($order)
    {
        $order = $this->findModel($order);

        dd("qua continuare con la cancellazione");

        return $this->_edit($order);
    }
}
