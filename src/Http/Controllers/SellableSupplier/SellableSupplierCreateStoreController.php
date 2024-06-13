<?php

namespace IlBronza\Products\Http\Controllers\SellableSupplier;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;

class SellableSupplierCreateStoreController extends SellableSupplierCRUD
{
    use CRUDCreateStoreTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = [
        'create',
        'store',
    ];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.sellableSupplier.parametersFiles.create');
    }
}
