<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Traits\SellableSupplierAssignmentTrait;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use Illuminate\Http\Request;

use function class_basename;

class QuotationrowAssignSellableSupplierController extends QuotationrowCRUD
{
	public string $targetType;
	public Quotationrow $quotationrow;
	public Sellable $sellable;

	public string $containerModelPrefix = 'quotation';

	use SellableSupplierAssignmentTrait;
	use CRUDIndexTrait;

	public $allowedMethods = ['assignSellableSupplier', 'associateSellableSupplier'];

	public function assignSellableSupplier(Request $request, $quotationrow)
	{
		$this->quotationrow = Quotationrow::getProjectClassName()::with('sellable')->find($quotationrow);
		$this->sellable = $this->quotationrow->getSellable();
		$this->targetType = class_basename($this->sellable->getTarget());

		return $this->_index($request);
	}

	public function associateSellableSupplier($quotationrow, $sellableSupplier)
	{
		$target = Quotationrow::getProjectClassName()::find($quotationrow);

		return $this->_associateSellableSupplier($target, $sellableSupplier);
	}
}
