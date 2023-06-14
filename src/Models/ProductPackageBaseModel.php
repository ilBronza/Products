<?php

namespace IlBronza\Products\Models;

use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;

class ProductPackageBaseModel extends BaseModel
{
	use ProductPackageBaseModelTrait;
	use CRUDUseUuidTrait;
    use InteractsWithNotesTrait;

	protected $keyType = 'string';
}