<?php

namespace IlBronza\Products\Http\Controllers\Accessory;

use IlBronza\Buttons\Button;
use IlBronza\CRUD\Http\Controllers\Traits\StandardTraits\PackageStandardIndexTrait;

use function app;
use function __;

class AccessoryIndexController extends AccessoryCRUD
{
	use PackageStandardIndexTrait;

	public function addIndexButtons()
	{
		$this->getTable()->addButton(
			Button::create([
				'translatedText' => __('products::fields.accessoryTypes'),
				'icon' => 'tags',
				'href' => app('products')->route('accessoryTypes.index')
			])
		);
	}
}
