<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Controllers\Supplier\SupplierCRUD;
use IlBronza\Products\Models\Order;
use Illuminate\Http\Request;

class OrderAddSupplierIndexByTableController extends SupplierCRUD
{
	use CRUDIndexTrait;

	public $avoidCreateButton = true;
	public $rowSelectCheckboxes = false;
	public bool|string $caption = false;

	public Order $order;
	public string $type;

	public $allowedMethods = ['index'];

	public function getIndexElements()
	{
		$getterMethod = "get{$this->type}SellableSuppliers";

		if(method_exists(Order::gpc(), $getterMethod))
		{
			app('uikittemplate')->addCustomGetter($getterMethod);

			return Order::gpc()::$getterMethod();
		}

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

	public function index(Request $request, $order, $type)
	{
		$this->type = $type;
		$this->order = Order::gpc()::find($order);

		return $this->_index($request);
	}

	public function getRowcontainerModel()
	{
		return $this->order;
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
