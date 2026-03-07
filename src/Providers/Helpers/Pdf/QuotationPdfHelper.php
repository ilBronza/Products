<?php

namespace IlBronza\Products\Providers\Helpers\Pdf;

use IlBronza\Products\Models\Quotations\Quotation;

use function config;

class QuotationPdfHelper extends BasePdfHelper
{
	public function __construct(
		Quotation $quotation
	) {
		parent::__construct($quotation);
	}

	public function getQuotation() : Quotation
	{
		return $this->getContainer();
	}

	protected function getRowsRelation() : string
	{
		return 'quotationrows';
	}

	protected function getDocumentTitleDefault() : string
	{
		return 'Preventivo';
	}

	public function getViewName() : string
	{
		return config('products.pdf.quotationView', 'products::pdf.quotation');
	}
}
