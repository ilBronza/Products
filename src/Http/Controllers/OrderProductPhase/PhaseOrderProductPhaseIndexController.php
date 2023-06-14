<?php

namespace IlBronza\Products\Http\Controllers\OrderProductPhase;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

class PhaseOrderProductPhaseIndexController extends OrderProductPhaseCRUD
{
    public $debug = true;

    public $allowedMethods = ['index'];

    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getRelatedFieldsArray()
    {
        return config('products.models.orderProductPhase.fieldsGroupsFiles.related')::getFieldsGroup();
    }
}
