<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\Products\Http\Controllers\Supplier\AddSupplierIndexByTableController;
use IlBronza\Products\Models\Quotations\Quotation;

class QuotationAddSupplierIndexByTableController extends AddSupplierIndexByTableController
{
	protected function getRowcontainerModelClass() : string
	{
		return Quotation::class;
	}
}
