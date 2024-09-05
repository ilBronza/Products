<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;

class QuotationShowController extends QuotationCRUD
{
    use CRUDShowTrait;
    use CRUDRelationshipTrait;

    public $allowedMethods = ['show'];

    public function getGenericParametersFile() : ? string
    {
		//QuotationEditUpdateFieldsetsParameters;
        return config('products.models.quotation.parametersFiles.show');
    }

    public function getRelationshipsManagerClass()
    {
        return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
    }

    public function show(string $quotation)
    {
        $quotation = $this->findModel($quotation);

        return $this->_show($quotation);
    }
}
