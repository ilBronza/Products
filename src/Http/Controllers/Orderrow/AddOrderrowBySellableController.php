<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Http\Controllers\Orderrow\OrderrowCRUD;
use IlBronza\Products\Http\Traits\SellableRowAssignmentTrait;
use IlBronza\Products\Models\Order;

class AddOrderrowBySellableController extends OrderrowCRUD
{
	public $allowedMethods = ['store'];

	use SellableRowAssignmentTrait;

	public function store($order, $sellable)
	{
		$container = Order::gpc()::find($order);

		return $this->addNewRowBySellable($container, $sellable);
	}
}
