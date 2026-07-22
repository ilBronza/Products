<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Traits\GlobalTimelineTrait;

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

		$ids = Orderrow::gpc()::select('id')->pluck('id');

		$orderrows = RowsFinderHelper::getCompositeRowCollectionByIds($ids);
		// $orderrows = Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();

		$groupItems = Sellable::gpc()::with('target')->get();

		$this->createGroupsByCollection($groupItems);

		$this->createItemsByCollectionAndGetter($orderrows, 'getSellable');

		return $this->sendResponse();
	}

	public function getTimelineCreateRowFormEndpoint() : ?string
	{
		return app('products')->route('sellables.timeline.createRowFormBySellable', [
			'iframed' => true,
		]);
	}
}
