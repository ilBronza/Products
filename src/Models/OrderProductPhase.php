<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseRelationshipsTrait;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;


class OrderProductPhase extends BaseModel
{
	static $modelConfigPrefix = 'orderProductPhase';

	use ProductPackageBaseModelTrait;
	use CRUDUseUuidTrait;
    use InteractsWithNotesTrait;

	use OrderProductPhaseRelationshipsTrait;

	protected $keyType = 'string';
}