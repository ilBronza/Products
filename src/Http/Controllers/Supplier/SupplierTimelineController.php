<?php

namespace IlBronza\Products\Http\Controllers\Supplier;

use Carbon\Carbon;
use IlBronza\Products\Models\Orders\Orderrow;

use Illuminate\Support\Str;

class SupplierTimelineController extends SupplierCRUD
{
	public $allowedMethods = [
		'timeline',
		'container'
	];

	public function container($supplier)
	{
		$modelInstance = $this->findModel($supplier);

		$buttons = [
		];

		$apiEndpoint = $modelInstance->getKeyedRoute('timeline');

		return view('crud::timeline.timeline', compact( 'apiEndpoint', 'modelInstance', 'buttons'));
	}

	public function timeline($supplier)
	{
		$supplier = $this->findModel($supplier);

		$orderrows = Orderrow::gpc()::with('order', 'sellable.target')->whereIn('sellable_supplier_id', $supplier->sellableSuppliers()->select('id')->pluck('id'))->get();

		$groups = [];
		$items = [];

		$inserted = [];

		foreach($orderrows as $row)
		{
			$sellable = $row->getSellable();

			if(! ($inserted[$sellable->getKey()] ?? false))
			{
				$inserted[$sellable->getKey()] = true;

				$backgroundColor = $sellable->getTarget()?->getCssBackgroundColorValue() ?: '#cccccc';
				$groupTextColor = $sellable->getTarget()?->getCssTextColorValue() ?: '#000000';

				$groups[] = [
					'id' => $sellable->getKey(),
					'content' => $sellable->getName(),
					'style' => "background-color: {$backgroundColor}; color: {$groupTextColor}",
					'name' => $sellable->getName(),
					'className' => 'group-' . Str::slug($sellable->getName())
				];
			}

			$startsAt = $row->getStartsAt() ?? $row->getOrder()->getStartsAt() ?? Carbon::now();
			$endsAt = $row->getEndsAt() ?? $row->getOrder()->getEndsAt() ?? Carbon::now()->addHours(4);

			$links = [];

			$links[] = [
				'url' => $row->getAssignSellablesupplierUrl(),
				'target' => 'iframe',
				'faIcon' => 'shuffle',
			];

			$links[] = [
				'url' => $row->getModelContainer()->getGanttUrl(),
				'target' => 'iframe',
				'faIcon' => 'magnifying-glass',
			];

			$links[] = [
				'url' => $row->getModelContainer()->getGanttUrl(),
				'target' => '_blank',
				'faIcon' => 'chart-gantt',
			];

			$items[] = [
				'id' => $row->getKey(),
				//				'link' => $row->getSupplier()?->getGanttUrl(),
				'links' => $links,
				'start' => $startsAt->format('Y-m-d\TH:i:s'),
				'end' => $endsAt->format('Y-m-d\TH:i:s'),
				'progress' => $row->getCompletionPercentage(),
				'group' => $sellable->getKey(),
				'title' => $row->getOrder()?->getName(),
				'description' => $row->getDescription() ?? '',
				'className' => $row->getTimelineHtmlClassesString(),
//				'content' => ($row->getSupplier()?->getName() ?? $row->getSellable()?->getName() ?? 'Row ' . $row->getKey()) . ' (' . $startsAt->format('d-m H:i') . ' - ' . $endsAt->format('d-m H:i') . ')',

			];
		}

		return [
			'itemTemplate' => 'operator',
			'groups' => $groups,
			'items' => $items,
		];
	}}
