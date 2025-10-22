<?php

namespace IlBronza\Products\Http\Controllers\Accessory;

class AccessoryCreateByParentController extends AccessoryCreateStoreController
{
	public $allowedMethods = [
		'createByParent',
	];

	public function createByParent(string $string)
	{
		$parent = $this->findModel($string);

		$this->setParentModel($parent);

		return $this->create();
	}
}
