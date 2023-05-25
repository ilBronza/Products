<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;
use IlBronza\Products\Http\Controllers\Product\ProductCRUD;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProductShowFieldsetsParameters;
use IlBronza\Products\Providers\RelationshipsManagers\ProductRelationManager;

class ProductShowController extends ProductCRUD
{
    use CRUDRelationshipTrait;
    use CRUDShowTrait;

    public $allowedMethods = ['show'];

    public $parametersFile = ProductShowFieldsetsParameters::class;
    public $relationshipsManagerClass = ProductRelationManager::class;

    public function show($product)
    {
        $product = $this->getModelClass()::find($product);

        return $this->_show($product);
    }
}
