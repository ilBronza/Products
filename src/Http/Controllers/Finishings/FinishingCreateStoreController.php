<?php

namespace IlBronza\Products\Http\Controllers\Finishings;

use IlBronza\CRUD\Traits\CRUDCreateStoreTrait;
use IlBronza\CRUD\Traits\CRUDRelationshipTrait;

use function config;

class FinishingCreateStoreController extends FinishingCRUD
{
	use CRUDCreateStoreTrait;
	use CRUDRelationshipTrait;

	public $allowedMethods = [
		'create',
		'store',
	];

	public function getCreateParametersFile() : ? string
	{
		return config('products.models.finishing.parametersFiles.create');
	}
}