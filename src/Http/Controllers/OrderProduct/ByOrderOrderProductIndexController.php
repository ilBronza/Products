<?php

namespace IlBronza\Products\Http\Controllers\OrderProduct;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

class ByOrderOrderProductIndexController extends OrderProductCRUD
{
    public $allowedMethods = ['index'];

    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getRelatedFieldsArray()
    {
        return config('products.models.orderProduct.fieldsGroupsFiles.orderRelated')::getFieldsGroup();
    }
}
