<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\Form\Form;
use IlBronza\FormField\FormField;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\UikitTemplate\Fetcher;

use Illuminate\Http\Request;

use function array_keys;
use function dd;
use function implode;
use function route;
use function trans;
use function view;

class QuotationAddQuotationrowIndexController extends QuotationCRUD
{
	public $allowedMethods = ['addQuotationrow', 'storeQuotationrow'];

	public function addQuotationrow($quotation, string $type)
	{
		$quotation = $this->findModel($quotation);

		$types = [
			$type
		];

		$form = Form::createFromArray([
			'action' => $quotation->getStoreQuotationrowUrl(),
			'method' => 'POST'
		]);

		$form->addFetcher('outherRight', new Fetcher([
			'title' => 'Files correnti',
			'url' => route('docusign.filesList')
		]));

		foreach ($types as $type)
			$form->addFormField(
				FormField::createFromArray([
					'name' => $type,
					'label' => $type,
					'type' => 'json',
					'rules' => 'array|required',
					'fields' => [
						'sellable' => [
							'name' => 'sellable_' . $type,
							'label' => $type,
							'type' => 'select',
							'select2' => false,
							'rules' => 'string|required|in:' . implode(',', array_keys($quotation->getPossibleSellablesByType($type))),
							'list' => $quotation->getPossibleSellablesByType($type)
						],
						'quantity' => ['number' => 'integer|required|min:1']
					]
				])
			);

		return view('form::uikit.form', ['form' => $form]);
	}

	public function storeQuotationrow(Request $request, $quotation)
	{
		$quotation = $this->findModel($quotation);

		$types = [
			'Contracttype'
		];

		$validationParameters = [];

		foreach ($types as $type)
		{
			$validationParameters[$type] = 'array|required';
			$validationParameters[$type . '.*.sellable'] = 'string|required|in:' . implode(',', array_keys($quotation->getPossibleSellablesByType($type)));
			$validationParameters[$type . '.*.quantity'] = 'integer|required|min:1';
		}

		//		dd($request->all());
		//		dd($validationParameters);

		$parameters = $request->validate($validationParameters);

		$quotationrowSortingIndex = $quotation->quotationrows()->max('sorting_index') + 1;

		foreach ($parameters as $type => $sellables)
		{
			foreach ($sellables as $key => $_parameters)
			{
				$sellable = Sellable::getProjectClassName()::find($_parameters['sellable']);

				for ($i = 0; $i < $_parameters['quantity']; $i ++)
				{
					$quotationrow = Quotationrow::getProjectClassName()::make();
					$quotationrow->sellable()->associate($sellable);
					$quotationrow->quotation()->associate($quotation);

					$quotationrow->sorting_index = $quotationrowSortingIndex ++;

					$quotationrow->save();
				}
			}
		}

		return redirect()->to(
			$quotation->getEditUrl()
		);
	}

}

