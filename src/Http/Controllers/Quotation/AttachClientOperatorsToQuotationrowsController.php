<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use App\Providers\Helpers\ClientOperators\ClientOperatorInQuotationRestorerHelper;
use IlBronza\Products\Http\Controllers\Quotation\QuotationCRUD;

class AttachClientOperatorsToQuotationrowsController extends QuotationCRUD
{
	public $allowedMethods = ['attachClientOperatorsToQuotationrows'];

	public function attachClientOperatorsToQuotationrows(string $quotation)
	{
		$quotation = $this->getModelClass()::find($quotation);

		ClientOperatorInQuotationRestorerHelper::restoreByQuotationsIds([
			$quotation->getKey()
		]);

		return back();
	}
}
