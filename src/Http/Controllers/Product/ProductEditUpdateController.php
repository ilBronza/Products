<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use Illuminate\Http\Request;
use IlBronza\Products\Http\Controllers\Product\ProductCRUD;

class ProductEditUpdateController extends ProductCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.product.parametersFiles.edit');
    }

    public function edit($product)
    {
        $product = $this->findModel($product);

        return $this->_edit($product);
    }

    public function update(Request $request, $product)
    {
        $product = $this->findModel($product);

        return $this->_update($request, $product);
    }
}
