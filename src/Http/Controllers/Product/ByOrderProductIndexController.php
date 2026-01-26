<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

class ByOrderProductIndexController extends ProductCRUD
{
    public $allowedMethods = ['index'];

    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getRelatedFieldsArray()
    {
        return config('products.models.product.fieldsGroupsFiles.orderRelated')::getTracedFieldsGroup();
    }
}
