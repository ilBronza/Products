<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Http\Controllers\Orderrow\OrderrowCRUD;
use IlBronza\Products\Http\Traits\SellableSupplierAssignmentTrait;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Sellables\SellableSupplier;

class AddOrderrowBySellableSupplierController extends OrderrowCRUD
{
	public SellableSupplier $sellableSupplier;
	public $allowedMethods = ['store'];

	use SellableSupplierAssignmentTrait;

	public function store($order, $sellableSupplier)
	{
		$container = Order::gpc()::find($order);

		return $this->addNewRowBySellableSupplier($container, $sellableSupplier);
	}
}
