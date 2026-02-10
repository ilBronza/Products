<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\CRUD\Http\Controllers\Timeline\BaseTimelineController;
use IlBronza\CRUD\Traits\Timeline\GlobalTimelineTrait;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;

class GlobalOrderTimelineController extends BaseTimelineController
{
	use GlobalTimelineTrait;

	public function getEndpoint() : string
	{
		return app('products')->route('orders.globalTimeline');
	}

	public function getMainTimelineData()
	{
		$addContainerGantt = true;

		$orderrows = Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();

		$groupItems = Order::gpc()::whereIn('id', $orderrows->pluck('order_id'))->get();

		$this->createGroupsByCollection($groupItems);

		$this->createItemsByCollectionAndGetter($orderrows, 'getOrder');

		return $this->sendResponse();
	}

}
