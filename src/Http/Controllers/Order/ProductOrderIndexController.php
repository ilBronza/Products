<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;
use IlBronza\Products\Http\Controllers\Order\OrderCRUD;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\RelatedOrderFieldsGroupParametersFile;

class ProductOrderIndexController extends OrderCRUD
{
    public $debug = true;

    public $allowedMethods = ['index'];

    use CRUDPlainIndexTrait;
    use CRUDIndexTrait;

    public function getRelatedFieldsArray()
    {
        return RelatedOrderFieldsGroupParametersFile::getFieldsGroup();
    }
}
