<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use IlBronza\Products\Traits\Timelines\TimelineButtonsTrait;
use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Traits\GlobalTimelineTrait;

class GlobalOrderTimelineController extends BaseTimelineController
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
		return app('products')->route('orders.globalTimeline');
	}

	public function getContainerRouteName() : string
	{
		return 'orders.globalTimelineContainer';
	}

	public function getTimelineButtonsParameters() : array
	{
		return [
			'orders.globalTimelineContainer' => [
				'text' => 'products::timeline.orders',
				'parameters' => [],
			],
			'orders.bySuppliersTimelineContainer' => [
				'text' => 'products::timeline.ordersBySuppliers',
				'parameters' => ['option' => 'subgroups'],
			],
			'orders.bySellablesTimelineContainer' => [
				'text' => 'products::timeline.ordersBySellables',
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

		$groupItems = Order::gpc()::whereIn('id', $orderrows->pluck('order_id'))->get();

		$this->createGroupsByCollection($groupItems);

		$this->createItemsByCollectionAndGetter($orderrows, 'getOrder');

		return $this->sendResponse();
	}

	public function getTimelineCreateRowFormEndpoint() : ?string
	{
		return app('products')->route('orders.timeline.createRowFormByOrder', [
			'iframed' => true,
		]);
	}
}
