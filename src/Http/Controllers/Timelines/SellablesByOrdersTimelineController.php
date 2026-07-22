<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\ProductPackageBaseRowModel;
use Illuminate\Support\Collection;

class SellablesByOrdersTimelineController extends SellableSubgroupsTimelineController
{
	public function getSubgroupGetterMethod() : string
	{
		return 'getSellableOrderSubgroupTimelineGroup';
	}

	public function getEndpoint() : string
	{
		return app('products')->route('sellables.byOrdersTimeline', [
			'option' => $this->option ?? 'subgroups',
		]);
	}

	public function getContainerRouteName() : string
	{
		return 'sellables.byOrdersTimelineContainer';
	}

	//le righe orfane di contenitore qua non entrano
	protected function getSubgroupsTimelineItems() : Collection
	{
		return parent::getSubgroupsTimelineItems()
			->filter(fn (ProductPackageBaseRowModel $row) => $row->getModelContainer())
			->values();
	}
}
