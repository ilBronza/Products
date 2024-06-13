<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\CRUD\Traits\CRUDDeleteTrait;

class QuotationrowDestroyController extends QuotationrowCRUD
{
    use CRUDDeleteTrait;

    public $allowedMethods = ['destroy'];

    public function destroy($quotationrow)
    {
        $quotationrow = $this->findModel($quotationrow);

        return $this->_destroy($quotationrow);
    }
}