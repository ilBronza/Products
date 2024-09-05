<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;

class OrderrowCreateStoreController extends OrderrowCRUD
{
    use CRUDCreateStoreTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = [
        'create',
        'store',
    ];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.orderrow.parametersFiles.create');
    }
}
