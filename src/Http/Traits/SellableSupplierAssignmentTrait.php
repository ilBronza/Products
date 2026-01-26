<?php

namespace IlBronza\Products\Http\Traits;

use Exception;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;

use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsSellableSupplierAssociatorHelper;

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

		foreach($brothers as $brother)
			if(! $brother->getSellableSupplier())
				$this->__associateSellableSupplier($brother, $sellableSupplier);

		$this->setSellable($target->getSellable());

		return $this->closeIframe($this->getTablesToRefresh());
	}

	public function getTablesToRefresh() : array
	{
		$sellable = $this->getSellable();

		if ($sellable->isContracttype())
			return ['operatorRows'];

		else if ($sellable->isVehicleType())
			return ['vehicleRows'];

		else if ($sellable->isSurveillanceType())
			return ['surveillanceRows'];

		else if ($sellable->isHotelType())
			return ['hotelRows'];

		else if ($sellable->isRentType())
			return ['rentRows'];

		else if ($sellable->isControlRoomType())
			return ['controlRoomRows'];

		else if ($sellable->isReimbursementType())
			return ['reimbursementRows'];

		else
			throw new Exception('gestire gli altri tipi');
	}

	public function __associateSellableSupplier($target, $sellableSupplier) : void
	{
		RowsSellableSupplierAssociatorHelper::associateSellableSupplierToRow($target, $sellableSupplier);
	}

	public function closeIframe($tablesToRefresh)
	{
		return view('datatables::utilities.closeIframe', compact('tablesToRefresh'));
	}

	public function setSellable(Sellable $sellable)
	{
		$this->sellable = $sellable;
	}

	public function _associateSellableSupplier($target, $sellableSupplier)
	{
		$this->__associateSellableSupplier($target, $sellableSupplier);

		$this->setSellable($target->getSellable());

		return $this->closeIframe(
			$this->getTablesToRefresh()
		);
	}

	public function getIndexFieldsArray()
	{
		//SellableSupplierRentFieldsGroupParametersFile
		if(! $helper = config("products.models.sellableSupplier.fieldsGroupsFiles.{$this->getSellable()->getType()}"))
			throw new \Exception('declare helper class in config ' . "products.models.sellableSupplier.fieldsGroupsFiles.{$this->getSellable()->getType()}");

		// return config("products.models.sellableSupplier.fieldsGroupsFiles.{$this->getSellable()->getType()}")::getTracedFieldsGroupByContainerModel($this->getContainerModelPrefix());

		return config("products.models.sellableSupplier.fieldsGroupsFiles.{$this->getSellable()->getType()}")::getFieldsGroupByContainerModel($this->getContainerModelPrefix());

	}

	public function getContainerModelPrefix() : string
	{
		return $this->containerModelPrefix;
	}

}