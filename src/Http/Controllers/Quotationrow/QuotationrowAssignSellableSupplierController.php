<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use Exception;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierVehicletypeFieldsGroupParametersFile;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Http\Request;

use function class_basename;
use function compact;
use function config;
use function ff;

class QuotationrowAssignSellableSupplierController extends QuotationrowCRUD
{
	public string $targetType;
	public Quotationrow $quotationrow;
	public Sellable $sellable;

	use CRUDIndexTrait;

	public $allowedMethods = ['assignSellableSupplier', 'associateSellableSupplier'];

	public function getIndexFieldsArray()
	{
		if(! $this->getTargetType())
		{
			//SellableSupplierHotelFieldsGroupParametersFile
			if($this->getSellable()->isHotelType())
				return config('products.models.sellableSupplier.fieldsGroupsFiles.hotel')::getFieldsGroup();

			if($this->getSellable()->isRentType())
				return config('products.models.sellableSupplier.fieldsGroupsFiles.rent')::getFieldsGroup();

			if($this->getSellable()->isSurveillanceType())
				return config('products.models.sellableSupplier.fieldsGroupsFiles.surveillance')::getFieldsGroup();
		}

		if ($this->getTargetType() == 'Contracttype')
			//SellableSupplierContracttypeFieldsGroupParametersFile
			return config('products.models.sellableSupplier.fieldsGroupsFiles.contracttype')::getFieldsGroup();

		if ($this->getTargetType() == 'Type')
			//SellableSupplierVehicletypeFieldsGroupParametersFile
			return config('products.models.sellableSupplier.fieldsGroupsFiles.vehicletype')::getFieldsGroup();

		throw new Exception("Unsupported type");
	}

	/**
	 * @return string
	 */
	public function getTargetType() : string
	{
		return $this->targetType;
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function assignSellableSupplier(Request $request, $quotationrow)
	{
		$this->quotationrow = Quotationrow::getProjectClassName()::with('sellable')->find($quotationrow);
		$this->sellable = $this->quotationrow->getSellable();
		$this->targetType = class_basename($this->sellable->getTarget());

		return $this->_index($request);
	}

	public function getIndexElements()
	{
		return $this->sellable->sellableSuppliers()->with(
			$this->getSellableSupplierRelationsByTargetType()
		)->get();
	}

	public function getSellableSupplierRelationsByTargetType() : array
	{
		if(! $this->getTargetType())
		{
			if($this->getSellable()->isHotelType())
				return [
					'supplier.target'
				];

			if($this->getSellable()->isRentType())
				return [
					'supplier.target'
				];

			if($this->getSellable()->isSurveillanceType())
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

	public function associateSellableSupplier($quotationrow, $sellableSupplier)
	{
		$quotationrow = Quotationrow::getProjectClassName()::find($quotationrow);
		$sellableSupplier = SellableSupplier::getProjectClassName()::find($sellableSupplier);

		if($sellableSupplier->getSellable()->isContracttype())
			$tablesToRefresh = ['operatorQuotationrows'];

		else if($sellableSupplier->getSellable()->isVehicleType())
			$tablesToRefresh = ['vehicleQuotationrows'];

		else if($sellableSupplier->getSellable()->isSurveillanceType())
			$tablesToRefresh = ['surveillanceQuotationrows'];

		else if($sellableSupplier->getSellable()->isHotelType())
			$tablesToRefresh = ['hotelQuotationrows'];

		else if($sellableSupplier->getSellable()->isRentType())
			$tablesToRefresh = ['rentQuotationrows'];

		else
			throw new \Exception('gestire gli altri tipi');

		$quotationrow->sellableSupplier()->associate($sellableSupplier);
		$quotationrow->save();

		return view('datatables::utilities.closeIframe', compact('tablesToRefresh'));
	}
}
