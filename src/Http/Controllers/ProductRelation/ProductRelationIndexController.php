<?php

namespace IlBronza\Products\Http\Controllers\ProductRelation;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\ProductRelation\ProductRelationCRUD;

class ProductRelationIndexController extends ProductRelationCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getRelatedFieldsArray()
    {
        return config('products.models.productRelation.fieldsGroupsFiles.productRelated')::getTracedFieldsGroup();
    }

    public $allowedMethods = ['index'];

    public $avoidCreateButton = true;
}
