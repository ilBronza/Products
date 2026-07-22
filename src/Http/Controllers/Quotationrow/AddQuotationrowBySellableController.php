<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowCRUD;
use IlBronza\Products\Http\Traits\SellableRowAssignmentTrait;
use IlBronza\Products\Models\Quotations\Quotation;

class AddQuotationrowBySellableController extends QuotationrowCRUD
{
	public $allowedMethods = ['store'];

	use SellableRowAssignmentTrait;

	public function store($quotation, $sellable)
	{
		$container = Quotation::gpc()::find($quotation);

		return $this->addNewRowBySellable($container, $sellable);
	}
}
