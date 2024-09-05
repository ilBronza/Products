<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\PackagedBaseModel;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;

class ProductPackageBaseModel extends PackagedBaseModel
{
	use ProductPackageBaseModelTrait;
	use CRUDUseUuidTrait;
	use InteractsWithNotesTrait;

	static $packageConfigPrefix = 'products';

	protected $keyType = 'string';
}