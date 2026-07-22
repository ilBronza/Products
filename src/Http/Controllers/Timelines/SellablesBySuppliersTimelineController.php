<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\ProductPackageBaseRowModel;
use Illuminate\Support\Collection;

class SellablesBySuppliersTimelineController extends SellableSubgroupsTimelineController
{
	public function getSubgroupGetterMethod() : string
	{
		return 'getSellableSupplierSubgroupTimelineGroup';
	}

	public function getEndpoint() : string
	{
		return app('products')->route('sellables.bySuppliersTimeline', [
			'option' => $this->option ?? 'subgroups',
		]);
	}

	public function getContainerRouteName() : string
	{
		return 'sellables.bySuppliersTimelineContainer';
	}

	//il resolver puo' non trovare nessun gruppo: quelle righe qua non entrano
	protected function getSubgroupsTimelineItems() : Collection
	{
		return parent::getSubgroupsTimelineItems()
			->filter(fn (ProductPackageBaseRowModel $row) => $row->getSupplierTimelineGroup())
			->values();
	}
}
