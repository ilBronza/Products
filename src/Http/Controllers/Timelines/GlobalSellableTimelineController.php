<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use IlBronza\Products\Traits\Timelines\TimelineButtonsTrait;
use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Traits\GlobalTimelineTrait;

class GlobalSellableTimelineController extends BaseTimelineController
{
	//entrambi i trait portano getButtons(): vince quello coi bottoni veri
	use GlobalTimelineTrait, TimelineButtonsTrait
	{
		TimelineButtonsTrait::getButtons insteadof GlobalTimelineTrait;
	}

	//senza questo il titolo pagina cerca routes.xxx invece di products::routes.xxx
	public function getPackageConfigName()
	{
		return 'products';
	}

	public function getEndpoint() : string
	{
		return app('products')->route('sellables.globalTimeline');
	}

	public function getContainerRouteName() : string
	{
		return 'sellables.globalTimelineContainer';
	}

	public function getTimelineButtonsParameters() : array
	{
		return [
			'sellables.globalTimelineContainer' => [
				'text' => 'products::timeline.sellables',
				'parameters' => [],
			],
			'sellables.bySuppliersTimelineContainer' => [
				'text' => 'products::timeline.sellablesBySuppliers',
				'parameters' => ['option' => 'subgroups'],
			],
			'sellables.byOrdersTimelineContainer' => [
				'text' => 'products::timeline.sellablesByOrders',
				'parameters' => ['option' => 'subgroups'],
			],
		];
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
