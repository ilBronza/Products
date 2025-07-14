<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDBulkEditTrait;

class OrderBulkEditUpdateController extends OrderEditUpdateController
{
	use CRUDBulkEditTrait;

	public $allowedMethods = ['bulkEdit', 'bulkUpdate'];

	public ?bool $updateEditor = false;
	public array $keys;

	public function getEditParametersFile() : ?string
	{
		return config('products.models.order.parametersFiles.bulkEdit');
	}

}
