<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\Products\Http\Controllers\Supplier\FindOrAssociateSupplierController;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use Illuminate\Http\Request;

class QuotationrowFindOrAssociateSupplierController extends FindOrAssociateSupplierController
{
	public string $containerModelPrefix = 'quotation';

	public function index(Request $request, $quotationrow)
	{
		$this->row = Quotationrow::gpc()::with('sellable')->find($quotationrow);

		return $this->__index($request);
	}

	public function store($quotationrow, $supplier)
	{
		$this->row = Quotationrow::gpc()::with('sellable')->find($quotationrow);

		return $this->_store($supplier);
	}
}
