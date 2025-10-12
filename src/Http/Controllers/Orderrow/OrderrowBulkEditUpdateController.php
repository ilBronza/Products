<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDBulkEditTrait;

use function config;

class OrderrowBulkEditUpdateController extends OrderrowEditUpdateController
{
    use CRUDBulkEditTrait;

    public $allowedMethods = ['bulkEdit', 'bulkUpdate'];

    public function getEditParametersFile() : ?string
    {
	    return config('products.models.orderrow.parametersFiles.bulkEdit');
    }

    public function getBulkUpdateModelAction() : string
    {
        return app('products')->route('orderrows.bulkUpdate');
    }
}