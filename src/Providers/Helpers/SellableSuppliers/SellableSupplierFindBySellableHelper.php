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

		return $sellable->getSellableSupplierIndexRelations();

		// if($relations = config('products.models.sellable.associationRelations.' . $sellable->getType(), false))
		// 	return $relations;

		// if ($sellable->isContracttype())
		// 	return $this->getContracttypeRelations();

		// try
		// {
		// 	if (($sellable->isControlRoomType())||($sellable->isVehicleType()))
		// 		return [
		// 			'prices',
		// 			'supplier.target.extraFields',
		// 			'supplier.target',
		// 		];			
		// }
		// catch(\Exception $e)
		// {
		// 	Log::critical($e->getMessage());
		// 	Ukn::e("Rimuovi sta merdata: " .  $e->getMessage());

		// 	if ($sellable->isVehicleType())
		// 		return [
		// 			'prices',
		// 			'supplier.target.extraFields',
		// 			'supplier.target',
		// 		];
		// }


		// if (($this->getSellable()->isServiceType())||($sellable->isHotelType())||($sellable->isReimbursementType()))
		// 	return [
		// 		'supplier.target.address'
		// 	];


		// dd($sellable->getType());

		// dd('dopo');




		// if (! $this->getTargetType())
		// {
		// 	if ($this->getSellable()->isHotelType())
		// 		return [
		// 			'supplier.target'
		// 		];

		// 	if ($this->getSellable()->isSurveillanceType())
		// 		return [
		// 			'supplier.target'
		// 		];
		// }

		// if ($this->getTargetType() == 'Type')
		// 	return [
		// 		'supplier.target'
		// 	];

		// throw new Exception ('altro tipo di sellable: ' . $this->getTargetType());
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