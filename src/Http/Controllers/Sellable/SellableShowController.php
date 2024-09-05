<?php

namespace IlBronza\Products\Http\Controllers\Sellable;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

use function config;

class SellableShowController extends SellableCRUD
{
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['show'];

    public function getGenericParametersFile() : ? string
    {
		//SellableCreateStoreFieldsetsParameters
        return config('products.models.sellable.parametersFiles.show');
    }

    public function getRelationshipsManagerClass()
    {
        return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $sellable)
    {
        $sellable = $this->findModel($sellable);

        return $this->_show($sellable);
    }
}
