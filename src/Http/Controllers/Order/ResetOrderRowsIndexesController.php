<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;


class ResetOrderRowsIndexesController extends OrderCRUD
{
	public $allowedMethods = ['resetRowsIndexes'];

	public function resetRowsIndexes(string $order)
	{
		$order = $this->getModelClass()::find($order);

		foreach(config('products.models.order.rowTypes') as $rowType)
		{
			$orderRows = $order->$rowType()->orderBy('sorting_index')->get();

			$i = 1;

			foreach($orderRows as $orderRow)
			{
				$orderRow->sorting_index = $i ++;
				$orderRow->save();
			}
		}

		return back();
	}
}
