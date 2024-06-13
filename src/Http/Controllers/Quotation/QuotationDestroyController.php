<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class QuotationDestroyController extends QuotationCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($quotation)
    {
        $quotation = $this->findModel($quotation);

        return $this->_destroy($quotation);
    }
}