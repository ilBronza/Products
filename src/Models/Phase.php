<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Products\Models\Traits\Phase\PhaseRelationshipsTrait;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

class Phase extends BaseModel
{
	static $modelConfigPrefix = 'phase';

	use ProductPackageBaseModelTrait;
	use CRUDUseUuidTrait;
	use CRUDSluggableTrait;
    use InteractsWithNotesTrait;

	use PhaseRelationshipsTrait;

	protected $keyType = 'string';



}