<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;

class OrderCreateController extends OrderCRUD
{
    use CRUDCreateStoreTrait;

    public $allowedMethods = ['create', 'store'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.order.parametersFiles.create');
    }
}
