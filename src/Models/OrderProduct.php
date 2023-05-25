<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Products\Models\Traits\OrderProduct\OrderProductRelationshipsTrait;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;


class OrderProduct extends BaseModel
{
	static $modelConfigPrefix = 'orderProduct';

	use ProductPackageBaseModelTrait;
	use CRUDUseUuidTrait;
    use InteractsWithNotesTrait;

	use OrderProductRelationshipsTrait;

	protected $keyType = 'string';
}