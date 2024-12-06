<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Traits\SellableSupplierAssignmentTrait;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use Illuminate\Http\Request;

use function class_basename;

class OrderrowAssignSellableSupplierController extends OrderrowCRUD
{
	public string $targetType;
	public Orderrow $orderrow;
	public Sellable $sellable;

	public string $containerModelPrefix = 'order';

	use SellableSupplierAssignmentTrait;
	use CRUDIndexTrait;

	public $allowedMethods = ['assignSellableSupplier', 'associateSellableSupplier'];

	public function assignSellableSupplier(Request $request, $orderrow)
	{
		$this->orderrow = Orderrow::getProjectClassName()::with('sellable')->find($orderrow);
		$this->sellable = $this->orderrow->getSellable();
		$this->targetType = class_basename($this->sellable->getTarget());

		return $this->_index($request);
	}

	public function associateSellableSupplier($orderrow, $sellableSupplier)
	{
		$target = Orderrow::getProjectClassName()::find($orderrow);

		return $this->_associateSellableSupplier($target, $sellableSupplier);
	}
}
