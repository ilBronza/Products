<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Products\Models\Traits\Order\OrderRelationshipsTrait;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

class Order extends BaseModel
{
	static $modelConfigPrefix = 'order';

	use ProductPackageBaseModelTrait;
	use CRUDUseUuidTrait;
	use CRUDSluggableTrait;
    use InteractsWithNotesTrait;

	use OrderRelationshipsTrait;

	protected $keyType = 'string';
}