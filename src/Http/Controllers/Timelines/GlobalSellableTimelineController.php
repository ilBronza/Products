<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\CRUD\Http\Controllers\Timeline\BaseTimelineController;
use IlBronza\CRUD\Traits\Timeline\GlobalTimelineTrait;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;

class GlobalSellableTimelineController extends BaseTimelineController
{
	use GlobalTimelineTrait;

	public function getEndpoint() : string
	{
		return app('products')->route('sellables.globalTimeline');
	}

	public function getMainTimelineData()
	{
		$addContainerGantt = true;

		$orderrows = Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();

		$groupItems = Sellable::gpc()::with('target')->get();

		$this->createGroupsByCollection($groupItems);

		$this->createItemsByCollectionAndGetter($orderrows, 'getSellable');

		return $this->sendResponse();
	}

}
