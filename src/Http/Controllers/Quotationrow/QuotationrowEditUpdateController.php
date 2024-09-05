<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

class QuotationrowEditUpdateController extends QuotationrowCRUD
{
    use CRUDEditUpdateTrait;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.quotationrow.parametersFiles.edit');
    }

    public function edit(string $quotationrow)
    {
        $quotationrow = $this->findModel($quotationrow);

        return $this->_edit($quotationrow);
    }

    public function update(Request $request, $quotationrow)
    {
        $quotationrow = $this->findModel($quotationrow);

        return $this->_update($request, $quotationrow);
    }
}
