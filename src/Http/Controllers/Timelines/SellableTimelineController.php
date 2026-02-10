<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\CRUD\Helpers\TimelineHelpers\TimelineGroupCreatorHelper;
use IlBronza\CRUD\Helpers\TimelineHelpers\TimelineItemCreatorHelper;
use IlBronza\CRUD\Http\Controllers\Timeline\BaseTimelineController;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
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

	public function getMainTimelineData($sellable, bool $addContainerGantt = false)
	{
		$sellable = $this->findModel($sellable);
		$modelInstance = $sellable->getTarget();

		$sellableSuppliersIds = SellableSupplier::gpc()::getIdsBySellable($sellable);

		$orderrows = Orderrow::gpc()::whereIn('sellable_supplier_id', $sellableSuppliersIds)->get();
		$quotationRows = Quotationrow::gpc()::whereIn('sellable_supplier_id', $sellableSuppliersIds)->get();

		$rows = $orderrows->merge($quotationRows);

		$suppliers = Supplier::gpc()::all();

		foreach($suppliers as $supplier)
			$this->groups[] = TimelineGroupCreatorHelper::createGroupByModel($supplier);

		foreach($rows as $row)
		{
			$this->items[] = TimelineItemCreatorHelper::createItemByModel($row, $row->getSupplier());

			// $supplier = $row->getSupplier();

			// if(! ($inserted[$supplier->getKey()] ?? false))
			// {
			// 	$inserted[$supplier->getKey()] = true;

			// 	$backgroundColor = $supplier->getTarget()?->getCssBackgroundColorValue() ?: '#cccccc';
			// 	$groupTextColor = $supplier->getTarget()?->getCssTextColorValue() ?: '#000000';

			// 	$groups[] = [
			// 		'id' => $supplier->getKey(),
			// 		'content' => $supplier->getName(),
			// 		'style' => "background-color: {$backgroundColor}; color: {$groupTextColor}",
			// 		'name' => $supplier->getName(),
			// 		'className' => 'group-' . Str::slug($supplier->getName()),
			// 		'actions' => [
			// 			[
			// 				'action' => 'open',
			// 				'faIcon' => 'chart-gantt',
			// 				'title' => 'Gantt ' . $supplier->getName(),
			// 				'url' => $supplier->getGanttUrl()
			// 			]
			// 		]
			// 	];
			// }

			// $startsAt = $row->getStartsAt() ?? $row->getOrder()->getStartsAt() ?? Carbon::now();
			// $endsAt = $row->getEndsAt() ?? $row->getOrder()->getEndsAt() ?? Carbon::now()->addHours(4);

			// $links = [];
			// $rightLinks = [];

			// $links[] = [
			// 	'url' => $row->getAssignSellablesupplierUrl(),
			// 	'target' => 'iframe',
			// 	'faIcon' => 'shuffle',
			// ];

			// $rightLinks[] = [
			// 	'url' => $supplier->getGanttUrl(),
			// 	'target' => 'iframe',
			// 	'faIcon' => 'magnifying-glass',
			// ];

			// $rightLinks[] = [
			// 	'url' => $supplier->getGanttUrl(),
			// 	'target' => '_blank',
			// 	'faIcon' => 'chart-gantt',
			// ];

			// if($addContainerGantt)
			// 	$rightLinks[] = [
			// 		'url' => $row->getModelContainer()->getGanttUrl(),
			// 		'text' => $row->getModelContainer()->getName(),
			// 		'target' => '_blank',
			// 		'faIcon' => 'chart-gantt',
			// 		'htmlClasses' => ['uk-float-left'],
			// 	];

			// $items[] = [
			// 	'id' => $row->getKey(),
			// 	//				'link' => $row->getSupplier()?->getGanttUrl(),
			// 	'links' => $links,
			// 	'rightLinks' => $rightLinks,
			// 	'start' => $startsAt->format('Y-m-d\TH:i:s'),
			// 	'end' => $endsAt->format('Y-m-d\TH:i:s'),
			// 	'progress' => $row->getCompletionPercentage(),
			// 	'group' => $supplier->getKey(),
			// 	'style' => [
			// 		'backgroundColor' => $row->getBackgroundColor(),
			// 		'textColor' => $row->getCssTextColorValue()
			// 	],
			// 	'title' => $row->getSupplier()?->getTarget()->getShortName(),
			// 	'popupTitle' => $row->getSupplier()?->getTarget()->getName(),
			// 	'description' => $row->getDescription() ?? '',
			// 	'className' => $row->getTimelineHtmlClassesString(),
			// 	//				'content' => ($row->getSupplier()?->getName() ?? $row->getSellable()?->getName() ?? 'Row ' . $row->getKey()) . ' (' . $startsAt->format('d-m H:i') . ' - ' . $endsAt->format('d-m H:i') . ')',

			// ];
		}

		return $this->sendResponse();
	}

}
