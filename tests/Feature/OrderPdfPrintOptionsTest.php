<?php

namespace IlBronza\Products\Tests\Feature;

use IlBronza\Products\Http\Controllers\Order\OrderPdfController;
use IlBronza\Products\Tests\TestCase;

class OrderPdfPrintOptionsTest extends TestCase
{
	public function test_default_print_directories_expose_templates_and_contract_documents() : void
	{
		$controller = new class extends OrderPdfController
		{
			public function discoverPdfViews(string $directoryConfigKey, string $namespaceConfigKey) : array
			{
				return $this->getPdfViews($directoryConfigKey, $namespaceConfigKey);
			}
		};

		$templates = $controller->discoverPdfViews(
			'quotationTemplatesDirectory',
			'quotationTemplatesViewNamespace'
		);
		$documents = $controller->discoverPdfViews(
			'contractDocumentsDirectory',
			'contractDocumentsViewNamespace'
		);

		$this->assertSame('products::pdf.catering.quotation', $templates['quotation']['view']);
		$this->assertArrayHasKey('essenziale', $templates);
		$this->assertArrayHasKey('esperienza', $templates);
		$this->assertArrayHasKey('condizioni-generali', $documents);
		$this->assertArrayHasKey('informativa-privacy', $documents);
		$this->assertArrayHasKey('diritto-di-recesso', $documents);
	}
}
