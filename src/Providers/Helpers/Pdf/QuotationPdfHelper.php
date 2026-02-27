<?php

namespace IlBronza\Products\Providers\Helpers\Pdf;

use IlBronza\Products\Models\Quotations\Quotation;
use Illuminate\Support\Collection;

use function config;
use function view;

class QuotationPdfHelper
{
	public function __construct(
		protected Quotation $quotation
	) {}

	public function getQuotation() : Quotation
	{
		return $this->quotation;
	}

	/**
	 * Get operator quotationrows for PDF (operator type).
	 */
	public function getOperatorQuotationrows() : Collection
	{
		$quotation = $this->getQuotation();

		if (method_exists($quotation, 'operatorQuotationrows')) {
			return $quotation->operatorQuotationrows()->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])->orderBy('sorting_index')->get();
		}

		return $quotation->quotationrows()->where('type', 'operator')->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])->orderBy('sorting_index')->get();
	}

	/**
	 * Get product quotationrows for PDF (product type).
	 */
	public function getProductQuotationrows() : Collection
	{
		$quotation = $this->getQuotation();

		if (method_exists($quotation, 'productQuotationrows')) {
			return $quotation->productQuotationrows()->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])->orderBy('sorting_index')->get();
		}

		return $quotation->quotationrows()->where('type', 'product')->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])->orderBy('sorting_index')->get();
	}

	/**
	 * Get data array for the PDF view.
	 */
	public function getViewData() : array
	{
		return [
			'quotation' => $this->getQuotation(),
			'operatorQuotationrows' => $this->getOperatorQuotationrows(),
			'productQuotationrows' => $this->getProductQuotationrows(),
		];
	}

	/**
	 * Get the blade view name for the PDF.
	 */
	public function getViewName() : string
	{
		return config('products.pdf.quotationView', 'products::pdf.quotation');
	}

	/**
	 * Generate PDF and return the binary content.
	 */
	public function generate() : string
	{
		$viewName = $this->getViewName();
		$html = view($viewName, $this->getViewData())->render();

		return $this->htmlToPdf($html);
	}

	/**
	 * Convert HTML to PDF. Override in custom helper if using different PDF library.
	 */
	protected function htmlToPdf(string $html) : string
	{
		if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
			return \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->output();
		}

		// Fallback: return HTML for browser print or custom implementation
		return $html;
	}
}
