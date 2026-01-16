<?php

namespace IlBronza\Products\Http\Controllers\Order;

use Carbon\Carbon;
use IlBronza\Buttons\Button;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function compact;
use function view;

class OrderTimelineController extends OrderCRUD
{
	static array $availableOrderTimelineOptions = [
		'main',
//		'main-children',
//		'main-children-skills',
		'main-children-operators',
	];

	public $allowedMethods = [
		'timeline',
		'updateRow',
		'container'
	];

	public function container($order, string $option = 'main')
	{
		$modelInstance = $this->findModel($order);

		$button = RowsButtonsHelper::getAddTypedRowButtonSimpleGET($modelInstance, 'operator');
		$button->setHtmlClass('uk-margin-right');

		$buttons = [
			$button
		];

		if($modelInstance->isRoot())
			foreach(static::$availableOrderTimelineOptions as $availableOption)
			{
				$button = Button::create([
					'href' => $modelInstance->getGanttUrl($availableOption),
					'text' => "products::orders.ganttWithOptions.{$availableOption}",
					'icon' => 'chart-gantt'
				]);

				if($availableOption != $option)
					$button->setPrimary();
				else
					$button->setSecondary()->setDisabled();

				$buttons[] = $button;
			}

		$apiEndpoint = $modelInstance->getKeyedRoute(
			'timeline', [
				'option' => $option
			]
		);

		return view('crud::timeline.timeline', compact( 'apiEndpoint', 'modelInstance', 'buttons'));
	}

	public function updateRow(Request $request, $order)
	{
		$order = $this->findModel($order);

		$placeholder = $order->orderrows()->make();

		$validator = Validator::make($request->all(), [
			'item.id' => 'required|in:' . $order->rows()->select('id')->pluck('id')->implode(','),
			'item.start' => [
				'required',
				'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/'
			],
			'item.end' => [
				'required',
				'after:item.start',
				'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/'
			],
		]);

		if($validator->fails())
			return [
				'success' => false,
				'errors' => $validator->errors(),
			];

		$item = $request->item;

		$orderrow = $order->rows()->where('id', $item['id'])->first();

		$startDate = Carbon::parse($item['start'])->setTimezone(config('app.timezone'));
		$endDate = Carbon::parse($item['end'])->setTimezone(config('app.timezone'));

		$orderrow->starts_at = $startDate;
		$orderrow->ends_at = $endDate;

		$orderrow->save();
	}

	public function getMainChildrenOperatorsTimelineData($order)
	{
		$data = $this->getMainTimelineData($order);

		$order = $this->findModel($order);

		$children = $order->getChildren();

		foreach($children as $child)
		{
			$childData = $this->getMainTimelineData($child->getKey(), true);

			$data['groups'] = array_merge($data['groups'], $childData['groups']);
			$data['items'] = array_merge($data['items'], $childData['items']);
		}

		return $data;
	}

	public function getMainTimelineData($order, bool $addContainerGantt = false)
	{
		$order = $this->findModel($order);

		$groups = [];
		$items = [];

		$inserted = [];

		foreach($order->rows as $row)
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
					'className' => 'group-' . Str::slug($sellable->getName()),
					'actions' => [
						[
							'action' => 'open',
							'faIcon' => 'chart-gantt',
							'title' => 'Gantt ' . $sellable->getName(),
							'url' => "www.google.com",
						]
					]
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

			if($supplier = $row->getSupplier())
			{
				$links[] = [
					'url' => $supplier->getGanttUrl(),
					'target' => 'iframe',
					'faIcon' => 'magnifying-glass',
				];

				$links[] = [
					'url' => $supplier->getGanttUrl(),
					'target' => '_blank',
					'faIcon' => 'chart-gantt',
				];
			}

			if($addContainerGantt)
				$links[] = [
					'url' => $row->getModelContainer()->getGanttUrl(),
					'text' => $row->getModelContainer()->getName(),
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
				'title' => $row->getSupplier()?->getTarget()->getShortName(),
				'popupTitle' => $row->getSupplier()?->getTarget()->getName(),
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
	}

	public function timeline($order, string $option = 'main')
	{
		$method = 'get' . Str::studly($option) . 'TimelineData';

		return $this->$method($order);
	}
}
