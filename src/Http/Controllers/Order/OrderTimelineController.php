<?php

namespace IlBronza\Products\Http\Controllers\Order;

use Carbon\Carbon;
use IlBronza\Buttons\Button;
use IlBronza\CRUD\Helpers\TimelineHelpers\TimelineItemCreatorHelper;
use IlBronza\CRUD\Http\Controllers\Timeline\BaseTimelineController;
use IlBronza\CRUD\Traits\Gantt\CRUDHasGanttTrait;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsButtonsHelper;
use IlBronza\Timings\Helpers\TimingIntervalsHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function array_merge;
use function compact;
use function view;

class OrderTimelineController extends BaseTimelineController
{
	public ? string $option;

	static array $availableOrderTimelineOptions = [
		'main',
		'main-children',
//		'main-children-skills',
		'main-children-operators',
	];

	public $allowedMethods = [
		'timeline',
		'updateRow',
		'container'
	];

	public function getEndpoint() : string
	{
		return $this->getModel()->getKeyedRoute(
			'timeline', [
				'option' => $this->option
			]
		);
	}

	public function findModel(string $key, array $relations = []) : ?Model
	{
		return Order::gpc()::find($key);
	}

	public function getButtons() : Collection
	{
		$button = RowsButtonsHelper::getAddTypedRowButtonSimpleGET($this->getModel(), 'operator');
		$button->setHtmlClass('uk-margin-right');

		$buttons = [
			$button
		];

		if($parent = $this->getModel()->getParent())
		{
			$button = Button::create([
				'href' => $parent->getGanttUrl('main'),
				'text' => "products::orders.ganttWithOptions.parent",
				'icon' => 'chart-gantt'
			]);

			$button->setPrimary();

			$buttons[] = $button;
		}

		if(count($this->getModel()->getChildren()))
			foreach(static::$availableOrderTimelineOptions as $availableOption)
			{
				$button = Button::create([
					'href' => $this->getModel()->getGanttUrl($availableOption),
					'text' => "products::orders.ganttWithOptions.{$availableOption}",
					'icon' => 'chart-gantt'
				]);

				if($availableOption != $this->option)
					$button->setPrimary();
				else
					$button->setSecondary()->setDisabled();

				$buttons[] = $button;
			}

		return collect($buttons);
	}

	public function container($order, string $option = 'main')
	{
		$modelInstance = $this->findModel($order);

		$this->option = $option;
		$this->setModel($modelInstance);

		return $this->returnGanttContainer();
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

	public function getWholeOrderTimelineData($order)
	{
		dd('qua ricreare');
		// $order = $this->findModel($order);

		// $groups = [];
		// $items = [];

		// $rows = $order->rows;

		// if($rows->isEmpty())
		// 	return [
		// 		'itemTemplate' => 'order',
		// 		'groups' => [],
		// 		'items' => [],
		// 	];


		// 		$groupId = 'order-' . $order->getKey();
		// 		$groupName = $order->getName();

		// 		$groups[] = [
		// 			'id' => $groupId,
		// 			'content' => $groupName,
		// 			'name' => $groupName,
		// 			'className' => 'group-order-' . $order->getKey(),
		// 		];


		// 		$intervalsResult = TimingIntervalsHelper::getTimeIntervals($rows);

		// foreach($intervalsResult->intervals as $index => $interval)
		// {
		// 	dd('eravamo qua');
		// 	$items[] = TimelineItemCreatorHelper::createItemByModel($intervals);
		// 	// $start = $interval->getStart();
		// 	// $end = $interval->getEnd();


		// 	// $items[] = [
		// 	// 	'id' => 'order-item-' . $order->getKey() . '-' . $index,
		// 	// 	'group' => $groupId,
		// 	// 	'start' => $start->format('Y-m-d\TH:i:s'),
		// 	// 	'end' => $end->format('Y-m-d\TH:i:s'),
		// 	// 	'title' => $groupName,
		// 	// 	'content' => $groupName,
		// 	// 	'style' => [
		// 	// 		'backgroundColor' => $order->getBackgroundColor()
		// 	// 	],
		// 	// 	'className' => 'order-timeline-item',
		// 	// ];
		// }

		// return [
		// 	'itemTemplate' => 'order',
		// 	'groups' => $groups,
		// 	'items' => $items,
		// ];
	}

	public function getMainChildrenTimelineData($order)
	{
		$order = $this->findModel($order);

		$this->_getMainTimelineData($order);

		$children = $order->getChildren();

		$this->createGroupsByCollection($children);
		$this->createItemsByCollection($children);		

		return $this->sendResponse();
	}

	public function getMainChildrenOperatorsTimelineData($order)
	{
		$order = $this->findModel($order);

		$this->_getMainTimelineData($order);

		$children = $order->getChildren();

		$this->createGroupsByCollection($children);

		foreach($children as $child)
		{
			$this->createItemsByCollectionAndGetter($child->rows, 'getOrder');		
		}

		return $this->sendResponse();
	}

	public function _getMainTimelineData($order, bool $addContainerGantt = false)
	{
		$groupItems = Sellable::gpc()::with('target')->get();

		$this->createGroupsByCollection($groupItems);

		$this->createItemsByCollectionAndGetter($order->rows, 'getSellable');		
	}

	public function getMainTimelineData($order, bool $addContainerGantt = false)
	{
		$order = $this->findModel($order);

		$this->_getMainTimelineData($order, $addContainerGantt);

		return $this->sendResponse();
	}

	public function timeline($order, string $option = 'main')
	{
		$method = $this->getOptionMethod($option);

		return $this->$method($order);
	}
}
