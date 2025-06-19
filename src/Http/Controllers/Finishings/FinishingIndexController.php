<?php

namespace IlBronza\Products\Http\Controllers\Finishings;

use IlBronza\CRUD\Http\Controllers\Traits\PackageStandardIndexTrait;

class FinishingIndexController extends FinishingCRUD
{
	use PackageStandardIndexTrait;

	public $scopes = [
	];
}
