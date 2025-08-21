<?php

namespace IlBronza\Products\Models;

use IlBronza\CRUD\Traits\CRUDSluggableTrait;

class Workstation extends ProductPackageBaseModel
{
	use CRUDSluggableTrait;

	static $modelConfigPrefix = 'workstation';
}