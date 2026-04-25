<?php

namespace IlBronza\Products\Http\Controllers\AccessoryTypes;

use IlBronza\Buttons\Button;
use IlBronza\CRUD\Http\Controllers\Traits\StandardTraits\PackageStandardIndexTrait;

use function app;
use function __;

class AccessoryTypeIndexController extends AccessoryTypeCRUD
{
	use PackageStandardIndexTrait;

	public function addIndexButtons()
	{
		$this->getTable()->addButton(
			Button::create([
				'translatedText' => __('products::fields.accessories'),
				'icon' => 'puzzle-piece',
				'href' => app('products')->route('accessories.index')
			])
		);
	}
}

