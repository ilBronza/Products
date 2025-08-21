<?php

namespace IlBronza\Products\Http\Controllers\ProductRelation;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDPivotControllerTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;
use IlBronza\CRUD\Traits\CRUDShowTrait;
use IlBronza\Products\Models\Product\Product;

use function config;

class ProductRelationCreateStoreController extends ProductRelationCRUD
{
	public $returnBack = true;

	use CRUDCreateStoreTrait;
	use CRUDRelationshipTrait;

	use CRUDPivotControllerTrait;

	public $allowedMethods = ['createByProduct', 'store'];

	public function getGenericParametersFile() : ? string
	{
		return config('products.models.productRelation.parametersFiles.create');
	}

	public function createByProduct(string $product)
	{
		$product = Product::gpc()::find($product);

		$this->setParentModel($product);

		return $this->create();
	}

}
