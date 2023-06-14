<?php

namespace IlBronza\Products\Http\Controllers\AccessoryProduct;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\AccessoryProduct\AccessoryProductCRUD;

class AccessoryProductIndexController extends AccessoryProductCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getRelatedFieldsArray()
    {
        return config('products.models.accessoryProduct.fieldsGroupsFiles.productRelated')::getFieldsGroup();
    }

    public $allowedMethods = ['index'];

    public $avoidCreateButton = true;
}
