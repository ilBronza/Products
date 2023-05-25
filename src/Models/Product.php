<?php

namespace IlBronza\Products\Models;

use Carbon\Carbon;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDManyToManyTreeTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;
use IlBronza\Products\Models\Traits\Product\ProductRelationshipsTrait;

class Product extends BaseModel
{
	static $modelConfigPrefix = 'product';

	use ProductPackageBaseModelTrait;
	use CRUDUseUuidTrait;
	use CRUDSluggableTrait;
    use InteractsWithNotesTrait;

	use CRUDManyToManyTreeTrait;

	public function getManyToManyRelationClass() : string
	{
		return ProductRelation::class;
	}

	public function scopeCurrent($query)
	{
		$query->whereHas(
			'orderProducts',
			function($_query)
			{
			    return $_query->where('products__order_products.created_at', '>' , Carbon::now()->subYears(1));
			}
		);
	}

	use ProductRelationshipsTrait;

	protected $keyType = 'string';
}