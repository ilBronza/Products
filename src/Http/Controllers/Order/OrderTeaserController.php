<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDTeaserTrait;
use IlBronza\Products\Http\Controllers\Order\OrderCRUD;
use IlBronza\Products\Models\Order;

class OrderTeaserController extends OrderCRUD
{
    use CRUDRelationshipTrait;
    use CRUDTeaserTrait;

    public $allowedMethods = ['teaser'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.order.parametersFiles.teaser');
    }

    public function teaser(Order $order)
    {
        return $this->_teaser($order);
    }
}
