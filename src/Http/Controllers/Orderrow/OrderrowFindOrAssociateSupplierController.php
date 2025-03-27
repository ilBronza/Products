<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Http\Controllers\Supplier\FindOrAssociateSupplierController;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Http\Request;

class OrderrowFindOrAssociateSupplierController extends FindOrAssociateSupplierController
{
	public string $containerModelPrefix = 'order';

	public function index(Request $request, $orderrow)
	{
		$this->row = Orderrow::getProjectClassName()::with('sellable')->find($orderrow);

		return $this->__index($request);
	}

	public function store($orderrow, $supplier)
	{
		$this->row = Orderrow::gpc()::with('sellable')->find($orderrow);

		return $this->_store($supplier);
	}
}
