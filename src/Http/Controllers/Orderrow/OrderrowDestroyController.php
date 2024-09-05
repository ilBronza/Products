<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class OrderrowDestroyController extends OrderrowCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($orderrow)
    {
        $orderrow = $this->findModel($orderrow);

        return $this->_destroy($orderrow);
    }
}