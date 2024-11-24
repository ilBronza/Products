<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDFlatSortingTrait;

class OrderrowReorderController extends OrderrowCRUD
{
	use CRUDFlatSortingTrait;

	public $allowedMethods = ['storeMassReorder'];
}
