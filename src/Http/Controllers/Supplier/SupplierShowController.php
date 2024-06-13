<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;
use IlBronza\Products\Http\Controllers\Supplier\SupplierCRUD;

class SupplierShowController extends SupplierCRUD
{
    use CRUDProductPackageShowTrait;

    public $allowedMethods = ['show'];

    public function show($supplier)
    {
        $supplier = $this->findModel($supplier);

        return $this->_show($supplier);
    }
}
