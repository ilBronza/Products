<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\Products\Http\Controllers\Sellable\SellableCRUD;
use IlBronza\Products\Models\Order;
use Illuminate\Http\Request;

class OrderAddOrderrowIndexByTableController extends SellableCRUD
{
	public $avoidCreateButton = true;
	public $rowSelectCheckboxes = true;

	use CRUDIndexTrait;

	public Order $order;
	public string $type;

	public $allowedMethods = ['index'];

	public function getIndexElements()
	{
		return $this->getModelClass()::byType($this->type)->with(
			'target.categories', 'suppliers.target'
		)->get();
	}

	public function addIndexButtons()
	{
		$this->getTable()->createPostButtonSamePage([
			'href' => app('products')->route('orders.addOrderrow', ['order' => $this->order->getKey(), 'type' => $this->type]),
			'text' => 'products::buttons.addRows',
			'icon' => 'plus'
		]);
	}

	public function index(Request $request, $order, $type)
	{
		$this->type = $type;
		$this->order = Order::gpc()::find($order);

		return $this->_index($request);
	}

	public function getIndexFieldsArray()
	{
		return [
			'translationPrefix' => 'products::fields',
			'fields' => [
				'name' => 'flat',
				'categories_list' => 'flat',
				'suppliers' => 'relations.belongsToMany',
			]
		];
	}
}
