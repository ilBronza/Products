<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use Carbon\Carbon;
use IlBronza\CRUD\Helpers\TimelineHelpers\TimelineGroupCreatorHelper;
use IlBronza\CRUD\Helpers\TimelineHelpers\TimelineItemCreatorHelper;
use IlBronza\CRUD\Http\Controllers\Timeline\BaseTimelineController;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SupplierTimelineController extends BaseTimelineController
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
		return Supplier::gpc();
	}

	public function container($supplier)
	{
		$this->setModel(
			$this->findModel($supplier)
		);

		return $this->returnGanttContainer();
	}

	public function timeline($supplier)
	{
		$supplier = $this->findModel($supplier);

		$orderrows = Orderrow::gpc()::with('order', 'sellable.target')->whereIn('sellable_supplier_id', $supplier->sellableSuppliers()->select('id')->pluck('id'))->get();

		$sellables = Sellable::gpc()::all();

		foreach($sellables as $sellable)
			$this->groups[] = TimelineGroupCreatorHelper::createGroupByModel($sellable);

		foreach($orderrows as $row)
			$this->items[] = TimelineItemCreatorHelper::createItemByModel($row, $row->getSellable());

		return $this->sendResponse();
	}}
