<?php

namespace IlBronza\Products\Providers\Helpers\Pdf;

use Illuminate\Support\Collection;

use function config;
use function view;

abstract class BasePdfHelper
{
	public function __construct(
		protected object $container
	) {}

	/**
	 * Get the container model (Order or Quotation).
	 */
	public function getContainer() : object
	{
		return $this->container;
	}

	/**
	 * Get the rows relation name ('orderrows' or 'quotationrows').
	 */
	abstract protected function getRowsRelation() : string;

	/**
	 * Get the method name for operator rows ('operatorOrderrows' or 'operatorQuotationrows').
	 */
	protected function getOperatorRowsMethod() : string
	{
		return $this->getRowsRelation() === 'orderrows' ? 'operatorOrderrows' : 'operatorQuotationrows';
	}

	/**
	 * Get the method name for product rows ('productOrderrows' or 'productQuotationrows').
	 */
	protected function getProductRowsMethod() : string
	{
		return $this->getRowsRelation() === 'orderrows' ? 'productOrderrows' : 'productQuotationrows';
	}

	/**
	 * Get operator rows for PDF.
	 */
	public function getOperatorRows() : Collection
	{
		$container = $this->getContainer();
		$method = $this->getOperatorRowsMethod();

		if (method_exists($container, $method)) {
			return $container->{$method}()
				->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])
				->orderBy('sorting_index')
				->get();
		}

		return $container->{$this->getRowsRelation()}()
			->where('type', 'operator')
			->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])
			->orderBy('sorting_index')
			->get();
	}

	/**
	 * Get product rows for PDF.
	 */
	public function getProductRows() : Collection
	{
		$container = $this->getContainer();
		$method = $this->getProductRowsMethod();

		if (method_exists($container, $method)) {
			return $container->{$method}()
				->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])
				->orderBy('sorting_index')
				->get();
		}

		return $container->{$this->getRowsRelation()}()
			->where('type', 'product')
			->with(['sellableSupplier.sellable', 'sellableSupplier.supplier'])
			->orderBy('sorting_index')
			->get();
	}

	/**
	 * Get default document title for the PDF.
	 */
	protected function getDocumentTitleDefault() : string
	{
		return 'Documento';
	}

	/**
	 * Get data array for the PDF view.
	 */
	public function getViewData() : array
	{
		return [
			'container' => $this->getContainer(),
			'operatorRows' => $this->getOperatorRows(),
			'productRows' => $this->getProductRows(),
			'documentTitleDefault' => $this->getDocumentTitleDefault(),
		];
	}

	/**
	 * Get the blade view name for the PDF.
	 */
	abstract public function getViewName() : string;

	/**
	 * Generate PDF and return the binary content.
	 */
	public function generate() : string
	{
		$html = view($this->getViewName(), $this->getViewData())->render();

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

		return $html;
	}
}
