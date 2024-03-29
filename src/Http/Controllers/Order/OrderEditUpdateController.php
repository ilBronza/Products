<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use Illuminate\Http\Request;
use IlBronza\Products\Http\Controllers\Order\OrderCRUD;

class OrderEditUpdateController extends OrderCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.order.parametersFiles.edit');
    }

    public function edit($order)
    {
        $order = $this->findModel($order);

        return $this->_edit($order);
    }

    public function update(Request $request, $order)
    {
        $order = $this->findModel($order);

        return $this->_update($request, $order);
    }
}
