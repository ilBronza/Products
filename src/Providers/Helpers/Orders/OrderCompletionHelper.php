<?php

namespace IlBronza\Products\Providers\Helpers\Orders;

use Carbon\Carbon;
use IlBronza\CRUD\Traits\PackagedHelpersTrait;
use IlBronza\Products\Models\Order;

class OrderCompletionHelper
{
	use PackagedHelpersTrait;

	static $packageConfigPrefix = 'products';
	static $modelConfigPrefix = 'order';
	static $classConfigPrefix = 'completionHelper';


	static function complete(Order $order)
	{
		$order->setCompletedAt(Carbon::now());
		$order->save();
	}


	static function uncomplete(Order $order)
	{
		$order->setCompletedAt(null);
		$order->setLoadedAt(null);
		$order->save();
	}

	static function checkCompletion(Order $order)
	{
		if(! count($orderProducts = $order->getOrderProducts()))
			return ;

		foreach($orderProducts as $orderProduct)
			if(! $orderProduct->isCompleted())
				return static::uncomplete($order);

		return static::complete($order);
	}


}