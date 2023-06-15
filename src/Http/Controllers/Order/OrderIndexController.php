<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;

class OrderIndexController extends OrderCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getIndexFieldsArray()
    {
        return OrderFieldsGroupParametersFile::getFieldsGroup();
    }
	
    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;

    public function getIndexElements()
    {
        ini_set('max_execution_time', "120");
        ini_set('memory_limit', "2048M");

        return cache()->remember(

            Str::slug(get_class($this) . __METHOD__),
            3600 * 24,

            function()
            {
                return $this->getModelClass()::with([
                    'client' => function($query)
                    {
                        $query->select('id', 'name');
                    }
                ])->get();
            }
        );
    }

}