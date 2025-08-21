<?php

namespace IlBronza\Products\Http\Controllers\Phase;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;

use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\Products\Models\Product\Product;

class PhaseCreateStoreByProductController extends PhaseCRUD
{
	public $returnBack = true;

	use CRUDCreateStoreTrait;
	use CRUDRelationshipTrait;

	public $allowedMethods = ['createByProduct', 'store'];

	public function getCreateParametersFile() : ? string
	{
		return config('products.models.phase.parametersFiles.create');
	}

	public function createByProduct(string $product)
	{
		$product = Product::gpc()::find($product);

		$this->setParentModel($product);

		return $this->create();
	}

}
