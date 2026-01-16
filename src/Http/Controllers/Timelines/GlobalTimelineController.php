<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use Carbon\Carbon;
use IlBronza\Products\Http\Controllers\Sellable\SellableCRUD;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use function array_rand;
use function cache;

abstract class GlobalTimelineController extends SellableCRUD
{
	public $allowedMethods = [
		'timeline',
		'container'
	];

	abstract public function getEndpoint() : string;
	abstract public function getGroupSubject(Orderrow $row) : Model;

	abstract public function getRows() : Collection;

	public function container()
	{
//		$apiEndpoint = app('products')->route('orders.globalTimeline');

		$apiEndpoint = $this->getEndpoint();

		return view('crud::timeline.timeline', compact( 'apiEndpoint'));
	}

	public function getGroupIdForRow(Orderrow $row) : string
	{
		$subject = $this->getGroupSubject($row);

		return $subject->getKey();
	}

	public function calculateTextColorFromBackgroundColor(string $backgroundColor) : string
	{
		$backgroundColor = ltrim($backgroundColor, '#');

		$r = hexdec(substr($backgroundColor, 0, 2));
		$g = hexdec(substr($backgroundColor, 2, 2));
		$b = hexdec(substr($backgroundColor, 4, 2));

		$brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

		return ($brightness > 125) ? '#000000' : '#FFFFFF';
	}

	public function getGroupContentByRow(Orderrow $row) : string
	{
		return $this->getGroupSubject($row)?->getName() ?? 'Group ' . $this->getGroupIdForRow($row);
	}

	public function getGroupNameByRow(Orderrow $row) : string
	{
		return $this->getGroupSubject($row)?->getName() ?? 'Group ' . $this->getGroupIdForRow($row);
	}

	public function getGroupClassnameByRow(Orderrow $row) : string
	{
		return 'group-' . Str::slug($this->getGroupNameByRow($row));
	}

	public function getGroupColorByRow(Orderrow $row) : string
	{
		return cache()->remember(
			$this->getGroupSubject($row)->cacheKey('color'),
			3600,
			function() {
				$colors = [
					'#FF6B6B',
					'#4ECDC4',
					'#45B7D1',
					'#96CEB4',
					'#FFEEAD',
					'#D4A5A5',
					'#9B59B6',
					'#3498DB',
					'#E74C3C',
					'#2ECC71',
					'#F1C40F',
					'#1ABC9C'
				];

				return $colors[array_rand($colors)];
			});
	}

	public function getGroupTextColorByRow(Orderrow $row) : string
	{
		return $this->calculateTextColorFromBackgroundColor($this->getGroupColorByRow($row));
	}

	public function timeline()
	{
		$orderrows = $this->getRows();

		$groups = [];
		$items = [];

		$inserted = [];

		foreach($orderrows as $row)
		{
			$groupId = $this->getGroupIdForRow($row);

			if(! ($inserted[$groupId] ?? false))
			{
				$inserted[$groupId] = true;

				$backgroundColor = $this->getGroupColorByRow($row);
				$groupTextColor = $this->getGroupTextColorByRow($row);

				$groups[] = [
					'id' => $groupId,
					'content' => $this->getGroupContentByRow($row),
					'style' => "background-color: {$backgroundColor}; color: {$groupTextColor}",
					'name' => $this->getGroupNameByRow($row),
					'className' => $this->getGroupClassnameByRow($row)
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
				'url' => $row->getSupplier()->getGanttUrl(),
				'target' => 'iframe',
				'faIcon' => 'magnifying-glass',
			];

			$links[] = [
				'url' => $row->getSupplier()->getGanttUrl(),
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
				'group' => $this->getGroupIdForRow($row),
				'title' => $row->getOrder()?->getName() . ' - ' . $row->getSupplier()?->getTarget()->getShortName(),
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
}
