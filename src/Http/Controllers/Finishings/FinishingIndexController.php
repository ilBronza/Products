<?php

namespace IlBronza\Products\Http\Controllers\Finishings;

use IlBronza\CRUD\Http\Controllers\Traits\StandardTraits\PackageStandardIndexTrait;

class FinishingIndexController extends FinishingCRUD
{
	use PackageStandardIndexTrait;

	public $scopes = [
	];
}
