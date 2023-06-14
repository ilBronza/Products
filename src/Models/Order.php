<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\Products\Models\Traits\Order\OrderRelationshipsTrait;

class Order extends ProductPackageBaseModel
{
	use OrderRelationshipsTrait;
	use CRUDSluggableTrait;

	static $modelConfigPrefix = 'order';

	static $deletingRelationships = ['orderProducts'];
}