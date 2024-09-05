<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class SupplierDestroyController extends SupplierCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($upplier)
    {
        $upplier = $this->findModel($upplier);

        return $this->_destroy($upplier);
    }
}