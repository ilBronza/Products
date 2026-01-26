<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCRUD;

use function config;
use function dd;

class ToElaborateByWorkstationOrderProductPhaseIndexController extends OrderProductPhaseCRUD
{
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;

    public function getIndexFieldsArray()
    {
	    //ToElaborateOrderProductPhaseFieldsGroupParametersFile
        return config('products.models.orderProductPhase.fieldsGroupsFiles.toElaborate')::getTracedFieldsGroup();
    }

}
