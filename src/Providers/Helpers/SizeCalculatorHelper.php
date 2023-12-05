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

		if(! $stencil = $size->getSizeable()->getStencilOrRtsStencil())
			throw new \Exception('Modello mancante per questo prodotto ' . $this->orderProductPhase->getProduct()->getName());


		$helper->setSize($size);
		$helper->setPacking($size->getSizeable()->getPacking());
		$helper->setStencil($stencil);

		return $helper;
	}
}