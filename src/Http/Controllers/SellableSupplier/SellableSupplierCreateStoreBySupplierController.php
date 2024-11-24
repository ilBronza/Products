<?php

namespace IlBronza\Products\Http\Controllers\SellableSupplier;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\Products\Models\Sellables\Supplier;
use Illuminate\Http\Request;

class SellableSupplierCreateStoreBySupplierController extends SellableSupplierCreateStoreController
{
    public $returnBack = true;

    use CRUDCreateStoreTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = [
        'createBySupplier',
        'storeBySupplier'
    ];

    public function getGenericParametersFile() : ? string
    {
        //SellableSupplierCreateStoreBySupplierFieldsetsParameters
        return config('products.models.sellableSupplier.parametersFiles.createBySupplier');
    }

    public function createBySupplier($supplier)
    {
        $supplier = Supplier::gpc()::find($supplier);

        $this->setParentModel($supplier);

        return $this->create();
    }

    public function getStoreModelAction()
    {
        return $this->getModel()->getStoreBySupplierUrl();
    }

    public function storeBySupplier(Request $request)
    {
        return $this->store($request);
    }
}
