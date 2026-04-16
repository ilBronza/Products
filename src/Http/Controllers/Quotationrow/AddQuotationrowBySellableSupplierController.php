<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowCRUD;
use IlBronza\Products\Http\Traits\SellableSupplierAssignmentTrait;
use IlBronza\Products\Models\Quotations\Quotation;

class AddQuotationrowBySellableSupplierController extends QuotationrowCRUD
{
	public $allowedMethods = ['store'];

	use SellableSupplierAssignmentTrait;

	public function store($quotation, $sellableSupplier)
	{
		$container = Quotation::gpc()::find($quotation);

		return $this->addNewRowBySellableSupplier($container, $sellableSupplier);
	}
}
