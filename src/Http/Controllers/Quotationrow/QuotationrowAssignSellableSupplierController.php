<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use Exception;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Http\Request;

use function class_basename;
use function compact;
use function config;

class QuotationrowAssignSellableSupplierController extends QuotationrowCRUD
{
	public string $targetType;
	public Quotationrow $quotationrow;
	public Sellable $sellable;

	use CRUDIndexTrait;

	public $allowedMethods = ['assignSellableSupplier', 'associateSellableSupplier'];

	public function getIndexFieldsArray()
	{
		if ($this->getTargetType() == 'Contracttype')
			//SellableSupplierContracttypeFieldsGroupParametersFile
			return config('products.models.sellableSupplier.fieldsGroupsFiles.contracttype')::getFieldsGroup();

		dd('altro tipo di sellable: ' . $targetType);
	}

	/**
	 * @return string
	 */
	public function getTargetType() : string
	{
		return $this->targetType;
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

		else
			throw new \Exception('gestire gli altri tipi');

		$quotationrow->sellableSupplier()->associate($sellableSupplier);
		$quotationrow->save();

		return view('datatables::utilities.closeIframe', compact('tablesToRefresh'));
	}
}
