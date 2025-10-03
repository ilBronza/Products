<?php

namespace IlBronza\Products\Http\Controllers\Order;

use App\Providers\Helpers\ClientOperators\ClientOperatorInOrderRestorerHelper;

class AttachClientOperatorsToOrderrowsController extends OrderCRUD
{
	public $allowedMethods = ['attachClientOperatorsToOrderrows'];

	public function attachClientOperatorsToOrderrows(string $order)
	{
		$order = $this->getModelClass()::find($order);

		ClientOperatorInOrderRestorerHelper::restoreByOrdersIds([
			$order->getKey()
		]);

		return back();
	}
}
