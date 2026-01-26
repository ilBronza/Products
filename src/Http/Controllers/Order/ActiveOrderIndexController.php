<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use Illuminate\Support\Str;

class ActiveOrderIndexController extends OrderCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getIndexFieldsArray()
    {
        return config('products.models.order.fieldsGroupsFiles.active')::getTracedFieldsGroup();
    }

    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;

    public function getIndexElements()
    {
        ini_set('max_execution_time', "120");
        ini_set('memory_limit', "-1");

        return $this->getModelClass()::active()->with([
            'client' => function($query)
            {
                $query->select('id', 'name');
            }
        ])->get();
    }

}
