<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\ProductPackageBaseRowModel;
use Illuminate\Support\Collection;

class OrdersBySuppliersTimelineController extends OrderSubgroupsTimelineController
{
	public function getSubgroupGetterMethod() : string
	{
		return 'getOrderSupplierSubgroupTimelineGroup';
	}

	public function getEndpoint() : string
	{
		return app('products')->route('orders.bySuppliersTimeline', [
			'option' => $this->option ?? 'subgroups',
		]);
	}

	public function getContainerRouteName() : string
	{
		return 'orders.bySuppliersTimelineContainer';
	}

	//il resolver puo' non trovare nessun gruppo: quelle righe qua non entrano
	protected function getSubgroupsTimelineItems() : Collection
	{
		return parent::getSubgroupsTimelineItems()
			->filter(fn (ProductPackageBaseRowModel $row) => $row->getSupplierTimelineGroup())
			->values();
	}
}
