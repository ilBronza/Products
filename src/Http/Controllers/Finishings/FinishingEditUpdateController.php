<?php

namespace IlBronza\Products\Http\Controllers\Finishings;

use IlBronza\CRUD\Http\Controllers\Traits\PackageStandardEditUpdateTrait;

class FinishingEditUpdateController extends FinishingCRUD
{
	use PackageStandardEditUpdateTrait;

	public $allowedMethods = [
		'edit',
		'update',
	];
}