<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use IlBronza\CRUD\Traits\CRUDEditUpdateTrait;

use Illuminate\Http\Request;

use function config;

class SupplierEditUpdateController extends SupplierCRUD
{
	use CRUDEditUpdateTrait;

	public $allowedMethods = ['edit', 'update'];

	public function getGenericParametersFile() : ? string
	{
		return config('products.models.supplier.parametersFiles.edit');
	}

	public function getRelationshipsManagerClass()
	{
		return config("products.models.{$this->configModelClassName}.relationshipsManagerClasses.show");
	}

	public function edit(string $supplier)
	{
		$supplier = $this->findModel($supplier);

		return $this->_edit($supplier);
	}

	public function update(Request $request, $supplier)
	{
		$supplier = $this->findModel($supplier);

		return $this->_update($request, $supplier);
	}
}
