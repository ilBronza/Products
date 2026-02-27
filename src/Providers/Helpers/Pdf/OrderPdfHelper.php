<?php

namespace IlBronza\Products\Providers\Helpers\Pdf;

use IlBronza\Products\Models\Order;
use Illuminate\Support\Collection;

use function config;
use function view;

class OrderPdfHelper
{
	public function __construct(
		protected Order $order
	) {}

	public function getOrder() : Order
	{
		return $this->order;
	}

	/**
	 * Get operator orderrows for PDF (OperatorOrderrow type).
	 */
	public function getOperatorOrderrows() : Collection
	{
		$order = $this->getOrder();

		if (method_exists($order, 'operatorOrderrows')) {
			return $order->operatorOrderrows()->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])->orderBy('sorting_index')->get();
		}

		return $order->orderrows()->where('type', 'operator')->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])->orderBy('sorting_index')->get();
	}

	/**
	 * Get product orderrows for PDF (ProductOrderrow type).
	 */
	public function getProductOrderrows() : Collection
	{
		$order = $this->getOrder();

		if (method_exists($order, 'productOrderrows')) {
			return $order->productOrderrows()->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])->orderBy('sorting_index')->get();
		}

		return $order->orderrows()->where('type', 'product')->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])->orderBy('sorting_index')->get();
	}

	/**
	 * Get data array for the PDF view.
	 */
	public function getViewData() : array
	{
		return [
			'order' => $this->getOrder(),
			'operatorOrderrows' => $this->getOperatorOrderrows(),
			'productOrderrows' => $this->getProductOrderrows(),
		];
	}

	/**
	 * Get the blade view name for the PDF.
	 */
	public function getViewName() : string
	{
		return config('products.pdf.orderView', 'products::pdf.order');
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
