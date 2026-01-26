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
        return config('products.models.quotationrow.fieldsGroupsFiles.index')::getTracedFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('products.models.quotationrow.fieldsGroupsFiles.related')::getTracedFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->getModelClass()::with(
            'quotation',
            'sellable',
            'clientOperator',
            'sellableSupplier.supplier.target',
            'client',
            'category',
            'extraFields'
        )->get();
    }

}
