<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Buttons\Button;

class CateringOrderEditUpdateController extends OrderEditUpdateController
{
	protected function addPdfNavbarButton($order) : void
	{
		$this->addNavbarButton(
			Button::create([
				'href' => $order->getKeyedRoute('cateringPdf'),
				'text' => 'products::orders.printPdf',
				'icon' => 'file-pdf',
				'target' => '_blank',
			])
		);
	}
}
