<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use Illuminate\Http\Request;

class OrderProductPhaseEditUpdateController extends OrderProductPhaseCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.orderProductPhase.parametersFiles.edit');
    }

    public function edit($orderProductPhase)
    {
        $orderProductPhase = $this->findModel($orderProductPhase);

        return $this->_edit($orderProductPhase);
    }

    public function getAfterUpdatedRedirectUrl()
    {
        return $this->getModel()->getOrder()->getOldShowOrderUrl();
    }

    public function update(Request $request, $orderProductPhase)
    {
        $orderProductPhase = $this->findModel($orderProductPhase);

        return $this->_update($request, $orderProductPhase);
    }
}
