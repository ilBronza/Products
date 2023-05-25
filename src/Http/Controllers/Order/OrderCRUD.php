<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\CRUD;

class OrderCRUD extends CRUD
{
    public function setModelClass()
    {
        $this->modelClass = config('products.models.order.class');
    }

}
