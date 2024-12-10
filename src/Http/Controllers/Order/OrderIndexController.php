<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use Illuminate\Support\Str;

use function ini_set;

class OrderIndexController extends OrderCRUD
{
    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getIndexFieldsArray()
    {
        return config('products.models.order.fieldsGroupsFiles.index')::getFieldsGroup();
    }

    public function getRelatedFieldsArray()
    {
        return config('products.models.order.fieldsGroupsFiles.related')::getFieldsGroup();
    }

    public $allowedMethods = ['index'];
    public $avoidCreateButton = true;

    public function getIndexElements()
    {
	    ini_set('max_execution_time', 300);
	    ini_set('memory_limit', - 1);

	    return $this->getModelClass()::with(
		    'extraFields', 'project', 'destination', 'parent', 'client', 'quotation',
	    )->get();
    }

}
