<?php

namespace IlBronza\Products\Http\Traits;

use Exception;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;

use function compact;
use function config;
use function dd;
use function view;

trait SellableSupplierAssignmentTrait
{
	public function getIndexElements()
	{
		return $this->sellable->sellableSuppliers()->with(
			$this->getSellableSupplierRelationsByTargetType()
		)->get();
	}

	public function getSellableSupplierRelationsByTargetType() : array
	{
		if (! $this->getTargetType())
		{
			if ($this->getSellable()->isHotelType())
				return [
					'supplier.target'
				];

			if ($this->getSellable()->isRentType())
				return [
					'supplier.target'
				];

			if ($this->getSellable()->isSurveillanceType())
				return [
					'supplier.target'
				];

			if ($this->getSellable()->isControlRoomType())
				return [
					'supplier.target'
				];
		}

		if ($this->getTargetType() == 'Type')
			return [
				'supplier.target'
			];

		if ($this->getTargetType() == 'Contracttype')
			return [
				'supplier.target.operatorContracttypes.contracttype',
				'supplier.target.operatorContracttypes.prices',
				'supplier.target.address'
			];

		throw new Exception ('altro tipo di sellable: ' . $this->getTargetType());
	}

	public function getTargetType() : string
	{
		return $this->targetType;
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function _associateSellableSupplier($target, $sellableSupplier)
	{
		$sellableSupplier = SellableSupplier::getProjectClassName()::find($sellableSupplier);

		if ($sellableSupplier->getSellable()->isContracttype())
			$tablesToRefresh = ['operatorRows'];

		else if ($sellableSupplier->getSellable()->isVehicleType())
			$tablesToRefresh = ['vehicleRows'];

		else if ($sellableSupplier->getSellable()->isSurveillanceType())
			$tablesToRefresh = ['surveillanceRows'];

		else if ($sellableSupplier->getSellable()->isHotelType())
			$tablesToRefresh = ['hotelRows'];

		else if ($sellableSupplier->getSellable()->isRentType())
			$tablesToRefresh = ['rentRows'];

		else if ($sellableSupplier->getSellable()->isControlRoomType())
			$tablesToRefresh = ['controlRoomRows'];

		else
			throw new Exception('gestire gli altri tipi');

		$target->sellableSupplier()->associate($sellableSupplier);
		$target->save();

		return view('datatables::utilities.closeIframe', compact('tablesToRefresh'));
	}

	public function getIndexFieldsArray()
	{
		if (! $this->getTargetType())
		{
			//SellableSupplierHotelFieldsGroupParametersFile
			if ($this->getSellable()->isHotelType())
				return config('products.models.sellableSupplier.fieldsGroupsFiles.hotel')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());

			if ($this->getSellable()->isRentType())
				return config('products.models.sellableSupplier.fieldsGroupsFiles.rent')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());

			if ($this->getSellable()->isSurveillanceType())
				return config('products.models.sellableSupplier.fieldsGroupsFiles.surveillance')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());

			if ($this->getSellable()->isControlRoomType())
				return config('products.models.sellableSupplier.fieldsGroupsFiles.controlRoom')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());
		}

		if ($this->getTargetType() == 'Contracttype')
			//SellableSupplierContracttypeFieldsGroupParametersFile
			return config('products.models.sellableSupplier.fieldsGroupsFiles.contracttype')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());

		if ($this->getTargetType() == 'Type')
			//SellableSupplierVehicletypeFieldsGroupParametersFile
			return config('products.models.sellableSupplier.fieldsGroupsFiles.vehicletype')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());

		dd($this);
		throw new Exception('Unsupported type');
	}

	public function getContainerModelPrefix() : string
	{
		return $this->containerModelPrefix;
	}

}