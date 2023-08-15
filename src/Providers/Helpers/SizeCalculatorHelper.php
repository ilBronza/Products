<?php

namespace IlBronza\Products\Providers\Helpers;

use IlBronza\Products\Models\Packing;
use IlBronza\Products\Models\Size;

class SizeCalculatorHelper
{
	public Size $size;

	public function setSize(Size $size)
	{
		$this->size = $size;
	}

	public function getSize() : Size
	{
		return $this->size;
	}

	public function setPacking(Packing $packing)
	{
		$this->packing = $packing;
	}

	public function getPacking() : Packing
	{
		return $this->packing;
	}

	static function createBySize(Size $size) : static
	{
		$helper = new static();

		$helper->setSize($size);
		$helper->setPacking($size->getSizeable()->getPacking());
		$helper->setStencil($size->getSizeable()->getStencil());

		return $helper;
	}
}