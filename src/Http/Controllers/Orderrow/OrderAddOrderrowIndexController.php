<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowCRUD;

use function config;

class OrderAddOrderrowIndexController extends OrderrowCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
        return config('products.models.orderrow.fieldsGroupsFiles.index')::getFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('products.models.orderrow.fieldsGroupsFiles.related')::getFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->getModelClass()::with('client', 'category')->get();
    }

}
