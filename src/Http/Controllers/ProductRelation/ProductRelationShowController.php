<?php

namespace IlBronza\Products\Http\Controllers\ProductRelation;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;
use IlBronza\Products\Http\Controllers\ProductRelation\ProductRelationCRUD;

class ProductRelationShowController extends ProductRelationCRUD
{
    use CRUDProductPackageShowTrait;
    public $allowedMethods = ['show'];

    public function show($productRelation)
    {
        $productRelation = $this->findModel($productRelation);

        return $this->_show($productRelation);
    }
}
