<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;

class OrderrowDestroyController extends OrderrowCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($orderrow)
    {
        $orderrow = RowsFinderHelper::getCustomSpecificRowById($orderrow);

        $this->deletedOrderrowOrder = $orderrow->getOrder();

        return $this->_destroy($orderrow);
    }
}