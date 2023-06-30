<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCRUD;

class ToElaborateByWorkstationOrderProductPhaseIndexController extends OrderProductPhaseCRUD
{
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;

    public function getIndexFieldsArray()
    {
        return config('products.models.orderProductPhase.fieldsGroupsFiles.toElaborate')::getFieldsGroup();
    }

}
