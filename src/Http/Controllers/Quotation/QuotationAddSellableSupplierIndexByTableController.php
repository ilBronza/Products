<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierCRUD;
use IlBronza\Products\Models\Quotations\Quotation;
use Illuminate\Http\Request;

class QuotationAddSellableSupplierIndexByTableController extends SellableSupplierCRUD
{
	public $avoidCreateButton = true;
	public $rowSelectCheckboxes = false;

	use CRUDIndexTrait;

	public Quotation $quotation;
	public string $type;

	public $allowedMethods = ['index'];

	public function getIndexElements()
	{
		$result = $this->getModelClass()::query()
			->whereHas('sellable', function($query)
			{
				$query->byType($this->type);
				$query->whereNull('deleted_at');
			})
			->with([
				'supplier.target',
				'sellable.target',
				'prices',
			])
			->get();

		return $result;
	}

	public function index(Request $request, $quotation, $type)
	{
		$this->type = $type;
		$this->quotation = Quotation::gpc()::find($quotation);

		return $this->_index($request);
	}

	public function getRowcontainerModel()
	{
		return $this->quotation;
	}

	public function getIndexFieldsArray()
	{
		$type = lcfirst($this->type);
		
		if(! $file = config("products.models.sellableSupplier.fieldsGroupsFiles.pick.{$type}"))
			$file = config("products.models.sellableSupplier.fieldsGroupsFiles.pick.index");

		return $file::getTracedFieldsGroup(
			$this->getRowcontainerModel()
		);
	}
}
