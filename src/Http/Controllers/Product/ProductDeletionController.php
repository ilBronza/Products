<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class ProductDeletionController extends ProductCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($product)
    {
        $product = $this->findModel($product);

        return $this->_destroy($product);
    }
}
