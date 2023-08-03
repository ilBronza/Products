<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;
use IlBronza\Products\Http\Controllers\Product\ProductCRUD;

class ProductShowController extends ProductCRUD
{
    use CRUDProductPackageShowTrait;
    public $allowedMethods = ['show'];

    public function show($product)
    {
        $product = $this->findModel($product, ['extraFields', 'size']);

        return $this->_show($product);
    }
}
