<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class OrderrowEditUpdateController extends OrderrowCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.orderrow.parametersFiles.create');
    }

    public function edit(string $orderrow)
    {
        $orderrow = $this->findModel($orderrow);

        return $this->_edit($orderrow);
    }

    public function update(Request $request, $orderrow)
    {
        $orderrow = $this->findModel($orderrow);

        return $this->_update($request, $orderrow);
    }
}
