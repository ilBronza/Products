<?php

namespace IlBronza\Products\Http\Controllers\SellableSupplier;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class SellableSupplierDestroyController extends SellableSupplierCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($sellableSupplier)
    {
        $sellableSupplier = $this->findModel($sellableSupplier);

        return $this->_destroy($sellableSupplier);
    }
}