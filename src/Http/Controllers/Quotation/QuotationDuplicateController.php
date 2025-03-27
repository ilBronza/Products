<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\Form\Form;
use IlBronza\FormField\FormField;
use Illuminate\Http\Request;

use function config;

class QuotationDuplicateController extends QuotationCRUD
{
	public $allowedMethods = ['duplicateForm', 'duplicate'];

	public function duplicateForm($quotation)
	{
		$quotation = $this->findModel($quotation);

		$form = Form::createFromArray([
			'action' => $quotation->getKeyedRoute('duplicate'),
			'method' => 'POST'
		]);

		$form->setCard();
		$form->setTitle(trans('products::quotations.duplicateQuotation', ['quotation' => $quotation->getName()]));


		$form->addFormField(
			FormField::createFromArray([
				'name' => 'event_starts_at',
				'label' => 'Data inizio evento',
				'type' => 'date'
			]));

		return $form->render();
	}

	public function validateRequest(Request $request) : array
	{
		return $request->validate([
			'event_starts_at' => 'date|nullable'
		]);
	}

	public function duplicate(Request $request, $quotation)
	{
		$requestParameters = $this->validateRequest($request);

		$quotation = $this->findModel($quotation);

		//QuotationDuplicatorHelper
		$helperClass = config('products.models.quotation.helpers.duplicate');

		$helper = new $helperClass($quotation, $requestParameters);

		$duplicatedQuotation = $helper->duplicate();

		return redirect()->to($duplicatedQuotation->getEditUrl());
	}
}

