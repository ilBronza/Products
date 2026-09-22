<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Buttons\Button;
use IlBronza\Form\Form;
use IlBronza\FormField\FormField;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

use function config;
use function response;

class OrderPdfController extends OrderCRUD
{
	public $allowedMethods = ['pdf'];

	public function pdf(Request $request, $order)
	{
		$order = $this->findModel($order);
		$templates = $this->getPdfViews(
			'quotationTemplatesDirectory',
			'quotationTemplatesViewNamespace'
		);

		if ($request->isMethod('get'))
			return $this->renderPdfOptionsForm($order, $templates, $request->url());

		$contractDocuments = $this->getPdfViews(
			'contractDocumentsDirectory',
			'contractDocumentsViewNamespace'
		);

		$parameters = $request->validate([
			'template' => ['required', 'string', Rule::in(array_keys($templates))],
			'contract_documents' => ['nullable', 'array'],
			'contract_documents.*' => ['string', Rule::in(array_keys($contractDocuments))],
		]);

		$helperClass = config('products.pdf.orderHelper');
		$helper = new $helperClass($order);
		$helper->setSelectedViewName($templates[$parameters['template']]['view']);
		$helper->setContractDocuments(array_values(array_intersect_key(
			$contractDocuments,
			array_flip($parameters['contract_documents'] ?? [])
		)));

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

	/**
	 * Build a print form from the configured Blade directories.
	 */
	protected function renderPdfOptionsForm($order, array $templates, string $action)
	{
		$contractDocuments = $this->getPdfViews(
			'contractDocumentsDirectory',
			'contractDocumentsViewNamespace'
		);

		$form = Form::createFromArray([
			'action' => $action,
			'method' => 'POST',
			'id' => 'order-pdf-options-' . $order->getKey(),
		]);

		$form->setCard();
		$form->setCancelButton(false);
		$form->setTitle('Stampa PDF: ' . ($order->getName() ?? 'Ordine'));
		$form->setSubmitButtonText('Genera PDF');
		$form->addCardClasses(['uk-width-large']);
		$form->addClosureButton(Button::create([
			'text' => 'Invia via email (prossimamente)',
			'disabled' => true,
			'classes' => ['uk-button', 'uk-button-default'],
		]));

		$form->addFormField(FormField::createFromArray([
			'name' => 'contract_documents',
			'label' => 'Documenti contrattuali',
			'type' => 'checkbox',
			'list' => $this->getCheckboxOptions($contractDocuments),
			'multiple' => true,
			'mustTranslateLabel' => false,
		]));

		$form->addFormField(FormField::createFromArray([
			'name' => 'template',
			'label' => 'Template del preventivo',
			'type' => 'radio',
			'list' => $this->getRadioOptions($templates),
			'default' => $this->getDefaultTemplate($templates),
			'required' => true,
			'mustTranslateLabel' => false,
		]));

		return view('products::pdf.printOptionsForm', [
			'form' => $form,
			'previewTarget' => 'pdf-preview-' . $order->getKey(),
		]);
	}

	/**
	 * Discover .blade.php files and turn them into selectable Laravel views.
	 */
	protected function getPdfViews(string $directoryConfigKey, string $namespaceConfigKey) : array
	{
		$directory = config("products.pdf.{$directoryConfigKey}");
		$namespace = trim((string) config("products.pdf.{$namespaceConfigKey}"), '.');

		if (! is_string($directory) || ! is_dir($directory) || $namespace === '')
			return [];

		$result = [];

		foreach (File::allFiles($directory) as $file)
		{
			$relativePath = $file->getRelativePathname();

			if (! str_ends_with($relativePath, '.blade.php'))
				continue;

			$id = substr($relativePath, 0, -strlen('.blade.php'));
			$id = str_replace(['/', '\\'], '.', $id);

			$result[$id] = [
				'view' => "{$namespace}.{$id}",
				'label' => str_replace(['-', '_', '.'], ' ', $id),
			];
		}

		uasort($result, static fn (array $first, array $second) => strnatcasecmp($first['label'], $second['label']));

		return $result;
	}

	/**
	 * CheckboxFormField uses the list value as the submitted value.
	 */
	protected function getCheckboxOptions(array $documents) : array
	{
		$result = [];

		foreach ($documents as $id => $document)
			$result[$document['label']] = $id;

		return $result;
	}

	protected function getRadioOptions(array $templates) : array
	{
		$result = [];

		foreach ($templates as $id => $template)
			$result[$id] = $template['label'];

		return $result;
	}

	protected function getDefaultTemplate(array $templates) : ?string
	{
		$default = config('products.pdf.defaultQuotationTemplate');

		if (isset($templates[$default]))
			return $default;

		return array_key_first($templates);
	}
}
