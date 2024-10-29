<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\CRUD\Traits\CRUDFlatSortingTrait;

class QuotationrowReorderController extends QuotationrowCRUD
{
	use CRUDFlatSortingTrait;

	public $allowedMethods = ['storeMassReorder'];
}
