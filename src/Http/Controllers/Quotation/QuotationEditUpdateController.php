<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;
use Illuminate\Http\Request;

use function config;

class QuotationEditUpdateController extends QuotationCRUD
{
    use CRUDEditUpdateTrait;

	public ? bool $updateEditor = true;

    public $allowedMethods = ['edit', 'update'];

    public function getGenericParametersFile() : ? string
    {
        return config('products.models.quotation.parametersFiles.edit');
    }

	public function getRelationshipsManagerClass()
	{
		return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
	}

	public function edit(string $quotation)
    {
        $quotation = $this->findModel($quotation);

        return $this->_edit($quotation);
    }

    public function update(Request $request, $quotation)
    {
        $quotation = $this->findModel($quotation);

        return $this->_update($request, $quotation);
    }
}
