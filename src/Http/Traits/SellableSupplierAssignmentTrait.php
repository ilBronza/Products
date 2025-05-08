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
	public $avoidCreateButton = true;
	public function getIndexElements()
	{
		$sellableSupplierHelper = config('products.models.sellableSupplier.helpers.findBySellableHelper');

		return $sellableSupplierHelper::findBySellable($this->sellable);
	}

	public function getTargetType() : string
	{
		return $this->targetType;
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function _associateBulkSellableSupplier($target, $sellableSupplier)
	{
		$type = $target->getSellable()->getType();

		$brothers = $target->getModelContainer()->rows()->bySellableType($type)->get();

		$tablesToRefresh = [];

		foreach($brothers as $brother)
			if(! $brother->getSellableSupplier())
				$tablesToRefresh = $this->__associateSellableSupplier($brother, $sellableSupplier);

		return $this->closeIframe($tablesToRefresh);
	}

	public function __associateSellableSupplier($target, $sellableSupplier)
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

		else if ($sellableSupplier->getSellable()->isReimbursementType())
			$tablesToRefresh = ['reimbursementRows'];

		else
			throw new Exception('gestire gli altri tipi');

		$target->sellableSupplier()->associate($sellableSupplier);
		$target->save();

		return $tablesToRefresh;
	}

	public function closeIframe($tablesToRefresh)
	{
		return view('datatables::utilities.closeIframe', compact('tablesToRefresh'));
	}

	public function _associateSellableSupplier($target, $sellableSupplier)
	{
		$tablesToRefresh = $this->__associateSellableSupplier($target, $sellableSupplier);

		return $this->closeIframe($tablesToRefresh);
	}

	public function getIndexFieldsArray()
	{
		//SellableSupplierRentFieldsGroupParametersFile
		if(! $helper = config("products.models.sellableSupplier.fieldsGroupsFiles.{$this->getSellable()->getType()}"))
			throw new \Exception('declare helper class in config ' . "products.models.sellableSupplier.fieldsGroupsFiles.{$this->getSellable()->getType()}");

		return config("products.models.sellableSupplier.fieldsGroupsFiles.{$this->getSellable()->getType()}")::getFieldsGroupByContainerModel($this->getContainerModelPrefix());

//		if (! $this->getTargetType())
//		{
//			//SellableSupplierHotelFieldsGroupParametersFile
//			if ($this->getSellable()->isHotelType())
//				return config('products.models.sellableSupplier.fieldsGroupsFiles.hotel')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());
//
//			if ($this->getSellable()->isRentType())
//				return config('products.models.sellableSupplier.fieldsGroupsFiles.rent')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());
//
//			if ($this->getSellable()->isSurveillanceType())
//				return config('products.models.sellableSupplier.fieldsGroupsFiles.surveillance')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());
//
//			if ($this->getSellable()->isControlRoomType())
//				return config('products.models.sellableSupplier.fieldsGroupsFiles.controlRoom')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());
//		}
//
//		if ($this->getTargetType() == 'Contracttype')
//			//SellableSupplierContracttypeFieldsGroupParametersFile
//			return config('products.models.sellableSupplier.fieldsGroupsFiles.contracttype')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());
//
//		if ($this->getTargetType() == 'Type')
//			//SellableSupplierVehicletypeFieldsGroupParametersFile
//			return config('products.models.sellableSupplier.fieldsGroupsFiles.vehicletype')::getFieldsGroupByContainerModel($this->getContainerModelPrefix());
//
//		dd($this);
//		throw new Exception('Unsupported type');
	}

	public function getContainerModelPrefix() : string
	{
		return $this->containerModelPrefix;
	}

}