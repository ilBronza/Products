<?php

namespace IlBronza\Products\Http\Controllers\Order;

use Illuminate\Http\Response;

use function config;
use function response;

class OrderPdfController extends OrderCRUD
{
	public $allowedMethods = ['pdf'];

	public function pdf($order)
	{
		$order = $this->findModel($order);

		$helperClass = config('products.pdf.orderHelper');
		$helper = new $helperClass($order);

		$content = $helper->generate();

		// If DomPDF was used, return PDF response
		if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
			return response($content, 200, [
				'Content-Type' => 'application/pdf',
				'Content-Disposition' => 'inline; filename="' . ($order->getName() ?? 'ordine') . '.pdf"',
			]);
		}

		// Fallback: return HTML for print
		return response($content, 200, [
			'Content-Type' => 'text/html; charset=UTF-8',
		]);
	}
}
