<?php

namespace IlBronza\Products\Http\Controllers\AccessoryProduct;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use Illuminate\Http\Request;

class AccessoryProductEditUpdateController extends AccessoryProductCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.accessoryProduct.parametersFiles.edit');
    }

    public function getAfterUpdatedRedirectUrl()
    {
        return $this->getModel()->getProduct()->getShowUrl();
    }

    public function edit($accessoryProduct)
    {
        $accessoryProduct = $this->findModel($accessoryProduct);

        return $this->_edit($accessoryProduct);
    }

    public function update(Request $request, $accessoryProduct)
    {
        $accessoryProduct = $this->findModel($accessoryProduct);

        return $this->_update($request, $accessoryProduct);
    }
}
