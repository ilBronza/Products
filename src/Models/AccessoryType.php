<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\Products\Models\Product\Product;

class AccessoryType extends ProductPackageBaseModel
{
	use CRUDParentingTrait;
	use CRUDSluggableTrait;

	static $modelConfigPrefix = 'accessoryType';

	public function products()
	{
		return $this->belongsToMany(
			Product::gpc(),
			config('products.models.accessoryTypeProduct.table'),
			'accessory_type_id',
			'product_id'
		)->using(
			AccessoryTypeProduct::gpc()
		);
	}
}

