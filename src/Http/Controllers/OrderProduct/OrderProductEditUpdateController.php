<?php

namespace IlBronza\Products\Http\Controllers\OrderProduct;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use Illuminate\Http\Request;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductCRUD;

class OrderProductEditUpdateController extends OrderProductCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.orderProduct.parametersFiles.edit');
    }

    public function edit($orderProduct)
    {
        $orderProduct = $this->findModel($orderProduct);

        return $this->_edit($orderProduct);
    }

    public function update(Request $request, $orderProduct)
    {
        $orderProduct = $this->findModel($orderProduct);

        return $this->_update($request, $orderProduct);
    }
}
