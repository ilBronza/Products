<?php

namespace IlBronza\Products\Providers\Helpers\SellableSuppliers;

use IlBronza\Products\Models\Sellables\Sellable;

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

	public function getContracttypeRelations() : array
	{
		return [
			'prices',
			'supplier.target.operatorContracttypes.contracttype',
			'supplier.target.operatorContracttypes.prices',
			'supplier.target.validClientOperator.employment',
			'supplier.target.user.userdata',
			'supplier.target.extraFields',
			'supplier.target.address'
		];
	}

	public function getSellableSupplierRelationsByTargetType() : array
	{
		$sellable = $this->getSellable();

		if ($sellable->isContracttype())
			return $this->getContracttypeRelations();

		if (($sellable->isControlRoomType())||($sellable->isVehicleType()))
			return [
				'prices',
				'supplier.target.extraFields',
				'supplier.target',
			];

		if (($this->getSellable()->isServiceType())||($sellable->isHotelType())||($sellable->isReimbursementType()))
			return [
				'supplier.target.address'
			];


		dd($sellable->getType());

		dd('dopo');




		if (! $this->getTargetType())
		{
			if ($this->getSellable()->isHotelType())
				return [
					'supplier.target'
				];

			if ($this->getSellable()->isSurveillanceType())
				return [
					'supplier.target'
				];
		}

		if ($this->getTargetType() == 'Type')
			return [
				'supplier.target'
			];

		throw new Exception ('altro tipo di sellable: ' . $this->getTargetType());
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