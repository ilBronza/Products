<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Traits\GlobalTimelineTrait;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Support\Collection;

class GlobalSellableSupplierTimelineController extends BaseTimelineController
{
	use GlobalTimelineTrait;

	public function getEndpoint() : string
	{
		return app('products')->route('sellableSuppliers.globalTimeline');
	}

	public function getRows() : Collection
	{
		return Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.sellable.target', 'sellableSupplier.supplier.target')->get();
	}

	public function getGroupItems(Collection $rows) : Collection
	{
		return $rows->map(fn($row) => $row->getSellableSupplier())
			->filter()
			->unique(fn($sellableSupplier) => $sellableSupplier->getKey())
			->values();
	}

	public function getMainTimelineData()
	{
		$orderrows = $this->getRows();

		$this->createGroupsByCollection(
			$this->getGroupItems($orderrows)
		);

		$this->createItemsByCollectionAndGetter($orderrows, 'getSellableSupplier');

		return $this->sendResponse();
	}
}
