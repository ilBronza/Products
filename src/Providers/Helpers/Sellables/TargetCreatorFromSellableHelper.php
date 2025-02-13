<?php

namespace IlBronza\Products\Providers\Helpers\Sellables;

use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Sellables\Sellable;

abstract class TargetCreatorFromSellableHelper
{
	public Sellable $sellable;
	public ? SellableItemInterface $target = null;

	abstract public function createTarget() : ? SellableItemInterface;
	abstract public function canCreateTarget() : bool;

	public function __construct(Sellable $sellable)
	{
		$this->sellable = $sellable;
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function getTarget() : ? SellableItemInterface
	{
		return $this->target;
	}
	
	public function provideTarget() : ? SellableItemInterface
	{
		if(! $this->canCreateTarget())
			return null;

		if($target = $this->getTarget())
			return $target;

		return $this->createTarget();
	}
}