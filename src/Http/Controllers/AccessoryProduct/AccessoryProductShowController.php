<?php

namespace IlBronza\Products\Http\Controllers\AccessoryProduct;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;

class AccessoryProductShowController //extends AccessoryProductRelationCRUD
{
    use CRUDProductPackageShowTrait;

    public $allowedMethods = ['show'];

    public function show($accessoryProduct)
    {
        $accessoryProduct = $this->findModel($accessoryProduct);

        return $this->_show($accessoryProduct);
    }
}
