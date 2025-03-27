<?php

namespace IlBronza\Products\Http\Controllers\Order;

class OrderFreezeController extends OrderCRUD
{
    public $allowedMethods = ['freeze'];

    public function freeze($order)
    {
        $order = $this->findModel($order);

		$helperClass = config('products.models.order.helpers.freezerHelper');

		$helper = new $helperClass($order);

	    if($order->isFrozen())
			return back();


		dd('consolida valori');
    }
}
