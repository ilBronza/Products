<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\CRUD\Http\Controllers\Timeline\BaseTimelineController;
use IlBronza\CRUD\Traits\Timeline\GlobalTimelineTrait;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Supplier;

class GlobalSupplierTimelineController extends BaseTimelineController
{
	use GlobalTimelineTrait;

	public function getEndpoint() : string
	{
		return app('products')->route('suppliers.globalTimeline');
	}

	public function getMainTimelineData()
	{
		$addContainerGantt = true;

		$orderrows = Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();

		$groupItems = Supplier::gpc()::with('target')->get();

		$this->createGroupsByCollection($groupItems);

		$this->createItemsByCollectionAndGetter($orderrows, 'getSupplier');

		return $this->sendResponse();
	}

}
