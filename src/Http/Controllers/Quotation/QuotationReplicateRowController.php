<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\Products\Http\Traits\RowContainerRowCopyTrait;
use IlBronza\Products\Models\Quotations\Quotation;

class QuotationReplicateRowController extends QuotationCRUD
{
	use RowContainerRowCopyTrait;

	public $allowedMethods = ['replicateLastRowByType'];

	public function replicateLastRowByType($quotation, string $type)
	{
		$quotation = Quotation::gpc()::find($quotation);

		return $this->_replicateLastRowByType($quotation, $type);
	}
}