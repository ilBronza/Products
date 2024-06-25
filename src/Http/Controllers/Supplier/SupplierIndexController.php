<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

use Illuminate\Support\Str;

class SupplierIndexController extends SupplierCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public $allowedMethods = ['index'];

    public function getIndexFieldsArray()
    {
        return config('products.models.supplier.fieldsGroupsFiles.index')::getFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('products.models.supplier.fieldsGroupsFiles.index')::getFieldsGroup();
    }

    public function getIndexElements()
    {
        return $this->getModelClass()::with(
            'categories',
            'target.destinations.address',
            'sellables'
        )
        ->withCount('quotationrows')
        ->get();
    }

}
