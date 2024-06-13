<?php

namespace IlBronza\Products\Http\Controllers\Sellable;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class SellableDestroyController extends SellableCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($sellable)
    {
        $sellable = $this->findModel($sellable);

        return $this->_destroy($sellable);
    }
}