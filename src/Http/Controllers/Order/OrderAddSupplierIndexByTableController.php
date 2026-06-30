<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Products\Http\Controllers\Supplier\AddSupplierIndexByTableController;
use IlBronza\Products\Models\Order;

class OrderAddSupplierIndexByTableController extends AddSupplierIndexByTableController
{
	protected function getRowcontainerModelClass() : string
	{
		return Order::class;
	}
}
