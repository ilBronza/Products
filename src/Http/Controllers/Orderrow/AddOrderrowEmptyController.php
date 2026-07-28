<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Http\Controllers\Orderrow\OrderrowCRUD;
use IlBronza\Products\Http\Traits\EmptyRowCreationTrait;
use IlBronza\Products\Models\Order;

class AddOrderrowEmptyController extends OrderrowCRUD
{
	public $allowedMethods = ['store'];

	use EmptyRowCreationTrait;

	public function store($order, $type)
	{
		$container = Order::gpc()::find($order);

		return $this->addNewEmptyRowByType($container, $type);
	}
}
