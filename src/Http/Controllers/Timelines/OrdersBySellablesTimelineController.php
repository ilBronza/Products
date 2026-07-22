<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\ProductPackageBaseRowModel;
use Illuminate\Support\Collection;

class OrdersBySellablesTimelineController extends OrderSubgroupsTimelineController
{
	public function getSubgroupGetterMethod() : string
	{
		return 'getOrderSellableSubgroupTimelineGroup';
	}

	public function getEndpoint() : string
	{
		return app('products')->route('orders.bySellablesTimeline', [
			'option' => $this->option ?? 'subgroups',
		]);
	}

	public function getContainerRouteName() : string
	{
		return 'orders.bySellablesTimelineContainer';
	}

	//le righe senza bene qua non entrano
	protected function getSubgroupsTimelineItems() : Collection
	{
		return parent::getSubgroupsTimelineItems()
			->filter(fn (ProductPackageBaseRowModel $row) => $row->getSellable())
			->values();
	}
}
