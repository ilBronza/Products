<?php

namespace IlBronza\Products\Models;

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

	use ProductRelationshipsTrait;

	protected $keyType = 'string';
}