<?php

namespace IlBronza\Products\Providers\Helpers\Sellables\TargetCreators;


use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Material;
use IlBronza\Products\Providers\Helpers\Sellables\TargetCreatorFromSellableHelper;

class MaterialFromSellableCreatorHelper extends TargetCreatorFromSellableHelper
{
	public function canCreateTarget() : bool
	{
		return true;
	}

	public function createTarget() : ? SellableItemInterface
	{
		$name = $this->getSellable()->getName();

		if($material = Material::gpc()::where('name', $name)->first())
			return $material;

		return Material::gpc()::createByName($name);
	}
}