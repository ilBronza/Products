<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowCRUD;

use function config;

class QuotationrowIndexController extends QuotationrowCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
        return config('products.models.quotationrow.fieldsGroupsFiles.index')::getFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('products.models.quotationrow.fieldsGroupsFiles.related')::getFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->getModelClass()::with('client', 'category')->get();
    }

}
