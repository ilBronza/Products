<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Http\Request;

use function request;

class OrderrowBySupplierIndexController extends OrderrowIndexController
{
    public $allowedMethods = ['index'];

	public function getIndexElements()
	{
		$sellableSupplierIds = SellableSupplier::gpc()::bySupplier(request()->supplier)->select('id')->pluck('id');

		return $this->getModelClass()::whereIn('sellable_supplier_id', $sellableSupplierIds)->get();
	}

}
