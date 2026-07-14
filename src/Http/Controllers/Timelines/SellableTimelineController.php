<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Timeline\Helpers\TimelineGroupCreatorHelper;
use IlBronza\Timeline\Helpers\TimelineItemCreatorHelper;
use IlBronza\Timeline\Http\Controllers\BaseTimelineController;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Support\Collection;

class SellableTimelineController extends BaseTimelineController
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
		return Sellable::gpc();
	}

	public function container($supplier)
	{
		$this->setModel(
			$this->findModel($supplier)
		);

		return $this->returnGanttContainer();
	}

	public function timeline($order, string $option = 'main')
	{
		$method = $this->getOptionMethod($option);

		return $this->$method($order);
	}

	public function getGroupModel($row) : ?TimelineGroupInterface
	{
		return $row->getSupplierTimelineGroup();
	}

	public function createGroupsByRows(Collection $rows) : void
	{
		$groupItems = $rows->map(fn($row) => $this->getGroupModel($row))
			->filter()
			->unique(fn(TimelineGroupInterface $group) => get_class($group) . ':' . $group->getTimelineGroupId())
			->values();

		foreach($groupItems as $groupItem)
			$this->groups[] = TimelineGroupCreatorHelper::createGroupByModel($groupItem);
	}

	public function getTimelineItemModalEndpoint() : string
	{
		return app('products')->route('sellables.timelineModal', [
			'iframed' => true,
		]);		
	}

	public function getMainTimelineData()
	{
		$sellable = $this->findModel(request()->sellable);

		$modelInstance = $sellable->getTarget();

		$sellableSuppliersIds = SellableSupplier::gpc()::getIdsBySellable($sellable);

		$orderrows = Orderrow::gpc()::with('order', 'sellable', 'sellableSupplier.supplier.target')->whereIn('sellable_supplier_id', $sellableSuppliersIds)->get();
		$quotationRows = Quotationrow::gpc()::with('quotation', 'sellable', 'sellableSupplier.supplier.target')->whereIn('sellable_supplier_id', $sellableSuppliersIds)->get();

		$rows = $orderrows->merge($quotationRows);

		$this->createGroupsByRows($rows);

		foreach($rows as $row)
			$this->items[] = TimelineItemCreatorHelper::createItemByModel($row, $this->getGroupModel($row));

		return $this->sendResponse();
	}

}
