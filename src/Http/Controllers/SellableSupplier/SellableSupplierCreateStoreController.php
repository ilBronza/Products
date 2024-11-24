<?php

namespace IlBronza\Products\Http\Controllers\SellableSupplier;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\Products\Models\Sellables\Supplier;

class SellableSupplierCreateStoreController extends SellableSupplierCRUD
{
	use CRUDCreateStoreTrait;
	use CRUDRelationshipTrait;

	public $allowedMethods = [
		'create',
		'store',
	];

	public function getGenericParametersFile() : ?string
	{
		return config('products.models.sellableSupplier.parametersFiles.create');
	}

	public function createBySupplier($supplier)
	{
		$supplier = Supplier::gpc()::find($supplier);

		$this->setParentModel($supplier);

		return $this->create();
	}
}
