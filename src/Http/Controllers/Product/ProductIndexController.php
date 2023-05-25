<?php

namespace IlBronza\Products\Http\Controllers\Product;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\Product\ProductCRUD;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ProductFieldsGroupParametersFile;
use Illuminate\Support\Str;

class ProductIndexController extends ProductCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getIndexFieldsArray()
    {
        return ProductFieldsGroupParametersFile::getFieldsGroup();
    }
	
    public $allowedMethods = ['index'];

    public function getIndexElements()
    {
        ini_set('max_execution_time', "120");
        ini_set('memory_limit', "2048M");

        return cache()->remember(

            Str::slug(get_class($this) . __METHOD__),
            3600 * 24,

            function()
            {
                return $this->getModelClass()::with('client', 'orders')->get();                
            }
        );
    }

    public $avoidCreateButton = true;
}
