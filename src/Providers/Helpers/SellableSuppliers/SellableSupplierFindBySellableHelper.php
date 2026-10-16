<?php

namespace IlBronza\Products\Providers\Helpers\SellableSuppliers;

use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Ukn\Ukn;
use Illuminate\Support\Facades\Log;

class SellableSupplierFindBySellableHelper
{
	public Sellable $sellable;

	public function setSellable(Sellable $sellable) : self
	{
		$this->sellable = $sellable;

		return $this;
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	// public function getContracttypeRelations() : array
	// {
	// 	//NADA, si usa public function getSellableSupplierIndexRelations() : array
	// 	//di Contracttype
	// }

	public function getSellableSupplierRelationsByTargetType() : array
	{
		$sellable = $this->getSellable();

		return $sellable->getTarget()->getSellableSupplierIndexRelations();
	}

	public function find()
	{
		return $this->getSellable()->sellableSuppliers()->with(
			$this->getSellableSupplierRelationsByTargetType()
		)->get();			
	}

	static function findBySellable(Sellable $sellable)
	{
		$helper = new static();
		$helper->setSellable($sellable);

		return $helper->find();
	}
}