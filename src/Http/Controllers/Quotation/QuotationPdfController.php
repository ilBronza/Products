<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use Illuminate\Http\Response;

use function config;
use function response;

class QuotationPdfController extends QuotationCRUD
{
	public $allowedMethods = ['pdf'];

	public function pdf($quotation)
	{
		$quotation = $this->findModel($quotation);

		$helperClass = config('products.pdf.quotationHelper');
		$helper = new $helperClass($quotation);

		$content = $helper->generate();

		// If DomPDF was used, return PDF response
		if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
			return response($content, 200, [
				'Content-Type' => 'application/pdf',
				'Content-Disposition' => 'inline; filename="' . ($quotation->getName() ?? 'preventivo') . '.pdf"',
			]);
		}

		// Fallback: return HTML for print
		return response($content, 200, [
			'Content-Type' => 'text/html; charset=UTF-8',
		]);
	}
}
