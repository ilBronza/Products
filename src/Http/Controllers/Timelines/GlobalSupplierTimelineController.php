<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use IlBronza\Timeline\Traits\GlobalTimelineTrait;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Supplier;
use Illuminate\Support\Collection;

class GlobalSupplierTimelineController extends BaseTimelineController
{
	use GlobalTimelineTrait;

	public function getEndpoint() : string
	{
		return app('products')->route('suppliers.globalTimeline');
	}

	public function getRows() : Collection
	{
		return Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->get();
	}

	public function getGroupModel($row) : ?TimelineGroupInterface
	{
		return $row->getSupplierTimelineGroup();
	}

	public function getGroupItems() : Collection
	{
		return Supplier::gpc()::with('target')->get()
			->map(fn(Supplier $supplier) => $supplier->getSupplierTimelineGroup())
			->filter()
			->unique(fn(TimelineGroupInterface $group) => get_class($group) . ':' . $group->getTimelineGroupId())
			->values();
	}

	public function getMainTimelineData()
	{
		$addContainerGantt = true;

		$orderrows = $this->getRows();

		$groupItems = $this->getGroupItems();

		$this->createGroupsByCollection($groupItems);

		$this->createItemsByCollectionAndGetter($orderrows, 'getSupplierTimelineGroup');

		return $this->sendResponse();
	}

}
