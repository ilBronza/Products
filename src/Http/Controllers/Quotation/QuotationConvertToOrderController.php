<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

class QuotationConvertToOrderController extends QuotationCRUD
{
	public $allowedMethods = ['convertToOrder'];

	public function convertToOrder($quotation)
	{
		$quotation = $this->findModel($quotation);

		$order = $quotation->convertToOrder();

		return redirect()->to($order->getEditUrl());
	}
}

