<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

class OrderrowShowController extends OrderrowCRUD
{
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['show'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.orderrow.parametersFiles.show');
    }

    public function getRelationshipsManagerClass()
    {
        return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $orderrow)
    {
        $orderrow = $this->findModel($orderrow);

        return $this->_show($orderrow);
    }
}
