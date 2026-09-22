<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;
use IlBronza\Products\Http\Controllers\Product\ProductCRUD;

use function config;

class ProductShowController extends ProductCRUD
{
    use CRUDProductPackageShowTrait;
    public $allowedMethods = ['show'];

    public function getRelationshipsManagerClass() : ?string
    {
        return $this->getModel()?->getRelationshipsManagerClass()
            ?? config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show($product)
    {
        $product = $this->findModel($product);

        return $this->_show($product);
    }
}
