<?php

namespace IlBronza\Products\Http\Controllers\Accessory;

use IlBronza\CRUD\Http\Controllers\Traits\StandardTraits\PackageStandardCreateStoreTrait;

class AccessoryCreateStoreController extends AccessoryCRUD
{
	use PackageStandardCreateStoreTrait;

	public function createByParent(string $string)
	{
		$parent = $this->findModel($string);

		dd($parent);

		$this->setParentModel($product);

		return $this->create();
	}

}
