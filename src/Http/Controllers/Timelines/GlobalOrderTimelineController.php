<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Traits\GlobalTimelineTrait;

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

		$ids = Orderrow::gpc()::select('id')->pluck('id');

		$orderrows = RowsFinderHelper::getCompositeRowCollectionByIds($ids);

		// $orderrows = Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();

		$groupItems = Order::gpc()::whereIn('id', $orderrows->pluck('order_id'))->get();

		$this->createGroupsByCollection($groupItems);

		$this->createItemsByCollectionAndGetter($orderrows, 'getOrder');

		return $this->sendResponse();
	}

	public function getTimelineCreateRowFormEndpoint() : ?string
	{
		return app('products')->route('orders.timeline.createRowFormByOrder', [
			'iframed' => true,
		]);
	}
}
