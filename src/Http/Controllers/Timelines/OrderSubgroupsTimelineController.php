<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use IlBronza\Products\Traits\Timelines\SubgroupsTimelineTrait;
use Illuminate\Support\Collection;

/**
 * Commesse con i figli annidati sotto. Le sottoclassi dichiarano solo
 * quale getter delle righe costruisce il gruppo composito.
 */
abstract class OrderSubgroupsTimelineController extends GlobalOrderTimelineController
{
	use SubgroupsTimelineTrait;

	//solo le commesse che hanno davvero delle righe
	protected function getParentTimelineGroups() : Collection
	{
		return Order::gpc()::whereIn(
			'id', $this->getSubgroupsTimelineItems()->pluck('order_id')
		)->get();
	}

	//una riga senza commessa non ha posto in una timeline delle commesse
	protected function getSubgroupsTimelineItems() : Collection
	{
		$ids = Orderrow::gpc()::select('id')->pluck('id');

		return RowsFinderHelper::getCompositeRowCollectionByIds($ids)
			->filter(fn (ProductPackageBaseRowModel $row) => $row->getModelContainer())
			->values();
	}
}
