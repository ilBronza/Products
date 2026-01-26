<?php

namespace IlBronza\Products\Http\Controllers\OrderProduct;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

class ByWorkstationOrderProductIndexController extends OrderProductCRUD
{
    public $allowedMethods = ['index'];

    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getIndexFieldsArray()
    {
        return config('products.models.orderProduct.fieldsGroupsFiles.workstationRelated')::getTracedFieldsGroup();
    }
}
