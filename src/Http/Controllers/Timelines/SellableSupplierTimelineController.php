<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Timeline\Helpers\TimelineItemCreatorHelper;
use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Support\Collection;

class SellableSupplierTimelineController extends BaseTimelineController
{
	public $allowedMethods = [
		'timeline',
		'container'
	];

	public function getEndpoint() : string
	{
		return $this->getModel()->getKeyedRoute('timeline');
	}

	public function getButtons() : Collection
	{
		return collect();
	}

	public function getModelClass() : string
	{
		return SellableSupplier::gpc();
	}

	public function container($sellableSupplier)
	{
		$this->setModel(
			$this->findModel($sellableSupplier)
		);

		return $this->returnGanttContainer();
	}

	public function timeline($sellableSupplier)
	{
		$sellableSupplier = $this->findModel($sellableSupplier);

		$orderrows = Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.sellable.target', 'sellableSupplier.supplier.target')
			->where('sellable_supplier_id', $sellableSupplier->getKey())
			->get();

		$this->createGroupsByCollection(collect([$sellableSupplier]));

		$this->createItemsByCollectionAndGetter($orderrows, 'getSellableSupplier');

		return $this->sendResponse();
	}
}
