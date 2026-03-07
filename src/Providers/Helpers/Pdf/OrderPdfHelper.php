<?php

namespace IlBronza\Products\Providers\Helpers\Pdf;

use IlBronza\Products\Models\Order;

use function config;

class OrderPdfHelper extends QuotationPdfHelper
{
	public function __construct(
		Order $order
	) {
		BasePdfHelper::__construct($order);
	}

	public function getOrder() : Order
	{
		return $this->getContainer();
	}

	protected function getRowsRelation() : string
	{
		return 'orderrows';
	}

	protected function getDocumentTitleDefault() : string
	{
		return 'Ordine';
	}

	public function getViewName() : string
	{
		return config('products.pdf.orderView', 'products::pdf.order');
	}
}
