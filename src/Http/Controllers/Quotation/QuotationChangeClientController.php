<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use Illuminate\Http\Request;
use IlBronza\Products\Http\Controllers\Quotation\QuotationEditUpdateController;

class QuotationChangeClientController extends QuotationEditUpdateController
{
	public ?bool $updateEditor = false;

	public $allowedMethods = ['edit', 'update'];

	public function getEditParametersFile() : ?string
	{
		return config('products.models.quotation.parametersFiles.changeClient');
	}

	public function edit($quotation)
	{
		$quotation = $this->findModel($quotation);

		return $this->_edit($quotation);
	}

	public function getRelationshipsManagerClass()
	{
		return null;
	}

	public function getUpdateModelAction()
	{
		return $this->getModel()->getKeyedRoute('changeClientUpdate');
	}

	public function update(Request $request, $quotation)
	{
		$quotation = $this->findModel($quotation);

		$quotation->project_id = null;

		return $this->_update($request, $quotation);
	}

	public function getAfterUpdateRoute()
	{
		return $this->getModel()->getEditUrl();
	}
}
