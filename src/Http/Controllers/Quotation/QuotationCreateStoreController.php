<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;

class QuotationCreateStoreController extends QuotationCRUD
{
    use CRUDCreateStoreTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = [
        'create',
        'store',
    ];

	public function getAfterStoredRedirectUrl()
	{
		return $this->getModel()->getEditUrl();
	}

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.quotation.parametersFiles.create');
    }
}
