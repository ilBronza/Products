<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use IlBronza\CRUD\Traits\IlBronzaPackages\CRUDExtraButtonsTrait;
use Illuminate\Http\Request;

use function app;
use function config;
use function trans;

class QuotationEditUpdateController extends QuotationCRUD
{
	use CRUDExtraButtonsTrait;
    use CRUDEditUpdateTrait;

	public ? bool $updateEditor = true;
    public $allowedMethods = ['edit', 'update'];

	public function getName() : ? string
	{
		return 'editquotation';
	}

	public function getId() : ? string
	{
		return null;
	}

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.quotation.parametersFiles.edit');
    }

	public function getRelationshipsManagerClass()
	{
		return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.edit");
	}

	public function edit(string $quotation)
    {
	    if(! $quotation = $this->findModel($quotation))
			abort(403);

		$this->addNavbarButton(
			$quotation->getChangeClientButton()
		);

	    if(! $quotation->hasOrder())
	    {
//			return redirect()->to($quotation->getShowUrl());

		    if($button = $quotation->getConvertToOrderButton())
			    $this->addNavbarButton(
				    $button
			    );
	    }

	    return $this->_edit($quotation);
    }

	public function manageBeforeEdit()
	{
		if($this->getModel()->hasOrder())
		{
			$this->getForm()->setTitle(
				trans('products::orders.orderFromQuotation', [
					'order' => $this->getModel()->getOrder()?->getName(),
					'quotation' => $this->getModel()->getName(),
					'orderUrl' => $this->getModel()->getOrder()?->getEditUrl()
				])
			);

			app('uikittemplate')->addBodyHtmlClass('order-quotation');
		}
	}


	public function update(Request $request, $quotation)
    {
        $quotation = $this->findModel($quotation);

        return $this->_update($request, $quotation);
    }

	public function getAfterUpdatedRedirectUrl()
	{
		return $this->getModel()->getEditUrl();
	}
}
