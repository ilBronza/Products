<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowCRUD;
use IlBronza\Products\Http\Traits\EmptyRowCreationTrait;
use IlBronza\Products\Models\Quotations\Quotation;

class AddQuotationrowEmptyController extends QuotationrowCRUD
{
	public $allowedMethods = ['store'];

	use EmptyRowCreationTrait;

	public function store($quotation, $type)
	{
		$container = Quotation::gpc()::find($quotation);

		return $this->addNewEmptyRowByType($container, $type);
	}
}
