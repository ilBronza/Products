<?php

namespace IlBronza\Products\Http\Controllers\SellableSupplier;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\Products\Models\Sellables\Sellable;
use Illuminate\Http\Request;

class SellableSupplierCreateStoreBySellableController extends SellableSupplierCreateStoreController
{
    public $returnBack = true;

    use CRUDCreateStoreTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = [
        'createBySellable',
        'storeBySellable'
    ];

    public function getGenericParametersFile() : ? string
    {
        //SellableSupplierCreateStoreBySellableFieldsetsParameters
        return config('products.models.sellableSupplier.parametersFiles.createBySellable');
    }

    public function createBySellable($sellable)
    {
        $sellable = Sellable::gpc()::find($sellable);

        $this->setParentModel($sellable);

        return $this->create();
    }

    public function getStoreModelAction()
    {
        return $this->getModel()->getStoreBySellableUrl();
    }

    public function storeBySellable(Request $request)
    {
        return $this->store($request);
    }
}
