<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use App\Models\ProjectSpecific\Supplier;
use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Controllers\CRUDProductPackageController;
use IlBronza\Products\Http\Traits\SellableSupplierAssignmentTrait;

use IlBronza\Products\Models\Sellables\Sellable;

use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsSellableSupplierAssociatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use Illuminate\Http\Request;

use function class_basename;
use function config;

class FindOrAssociateSupplierController extends CRUDProductPackageController
{
	use SellableSupplierAssignmentTrait;
	use CRUDIndexTrait;

	public $configModelClassName = 'supplier';

	public $allowedMethods = ['index', 'store'];

	public function getIndexElements()
	{
		if(($this->sellable->isRentType())||($this->sellable->isReimbursementType()))
			return Supplier::gpc()::where('target_type', 'Client')->with('target.address')->get();

		return Supplier::gpc()::with('target')->get();
	}

	public function getSellable() : Sellable
	{
		return $this->sellable;
	}

	public function getContainerModelPrefix()
	{
		return $this->containerModelPrefix;
	}

	public function getIndexFieldsArray()
	{
		//SupplierAssociationFieldsGroupParametersFile
		if (! $helper = config("products.models.supplier.fieldsGroupsFiles.associationBy.{$this->getSellable()->getType()}"))
			throw new \Exception("products.models.supplier.fieldsGroupsFiles.associationBy.{$this->getSellable()->getType()}");

		// return $helper::getTracedFieldsGroupByContainerModel($this->getContainerModelPrefix());
		return $helper::getFieldsGroupByContainerModel($this->getContainerModelPrefix());
	}

	public function __index(Request $request)
	{
		$this->sellable = $this->row->getSellable();
		$this->targetType = class_basename($this->sellable->getTarget());

		return $this->_index($request);

	}

	public function _store(string $supplier)
	{
		$this->sellable = $this->row->getSellable();
		$supplier = Supplier::gpc()::find($supplier);

		$sellableSupplier = RowsSellableSupplierAssociatorHelper::associateSellableSupplierToRowBySupplier($this->row, $supplier);

		$tablesToRefresh = $this->getTablesToRefresh();

		return $this->closeIframe($tablesToRefresh);


//		return $this->_associateSellableSupplier($this->row, $sellableSupplier->getKey());

	}
}