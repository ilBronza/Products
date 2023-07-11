<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use App\Models\ProductsPackage\OrderProduct;
use IlBronza\CRUD\Providers\RouterProvider\IbRouter;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCRUD;
use Illuminate\Http\Request;

class ByOrderProductOrderProductPhaseIndexController extends OrderProductPhaseCRUD
{
    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;

    use CRUDIndexTrait;

    public function getIndexFieldsArray()
    {
        return config('products.models.orderProductPhase.fieldsGroupsFiles.orderProductRelated')::getFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->orderProduct->orderProductPhases()->withOrderId()->withProductId()->get();
    }

    public function index(Request $request, OrderProduct $orderProduct)
    {
        $this->orderProduct = $orderProduct;

        return $this->_index($request);
    }
}
