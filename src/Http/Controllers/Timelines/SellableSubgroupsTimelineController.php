<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;
use IlBronza\Timeline\Helpers\TimelineGroupCreatorHelper;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use Illuminate\Support\Collection;

/**
 * Beni con i figli annidati sotto. Le sottoclassi dichiarano solo
 * quale getter delle righe costruisce il gruppo composito.
 */
abstract class SellableSubgroupsTimelineController extends GlobalSellableTimelineController
{
	public string $option = 'subgroups';

	abstract public function getSubgroupGetterMethod() : string;

	public function container(string $option = 'subgroups')
	{
		return parent::container($option);
	}

	public function timeline(string $option = 'subgroups')
	{
		return parent::timeline($option);
	}

	public function getSubgroupsTimelineData()
	{
		$rows = $this->getSubgroupsTimelineItems();
		$getter = $this->getSubgroupGetterMethod();

		//i subgroup nascono dalle stesse righe che poi diventano item:
		//gli id dei nestedGroups e quelli dei gruppi figli vengono
		//dalla stessa chiamata, quindi non possono disallinearsi
		$subgroups = $rows
			->map(fn (ProductPackageBaseRowModel $row) => $row->{$getter}())
			->unique(fn (TimelineGroupInterface $subgroup) => $subgroup->getTimelineGroupId())
			->values();

		$subgroupIdsBySellable = $subgroups
			->groupBy(fn (TimelineGroupInterface $subgroup) => $subgroup->getParent()->getTimelineGroupId())
			->map(fn (Collection $sellableSubgroups) => $sellableSubgroups
				->map(fn (TimelineGroupInterface $subgroup) => $subgroup->getTimelineGroupId())
				->values()
				->all()
			);

		foreach($this->getSellableTimelineGroups() as $sellable)
		{
			$group = TimelineGroupCreatorHelper::createGroupByModel($sellable);
			$nestedGroups = $subgroupIdsBySellable->get($sellable->getTimelineGroupId(), []);

			if($nestedGroups)
			{
				$group = get_object_vars($group);
				$group['nestedGroups'] = $nestedGroups;
				$group['showNested'] = true;
			}

			$this->groups[] = $group;
		}

		$this->createGroupsByCollection($subgroups);
		$this->createItemsByCollectionAndGetter($rows, $getter);

		return $this->sendResponse();
	}

	protected function getSellableTimelineGroups() : Collection
	{
		return Sellable::gpc()::with('target')->get();
	}

	//una riga senza bene non ha posto in una timeline dei beni
	protected function getSubgroupsTimelineItems() : Collection
	{
		$ids = Orderrow::gpc()::select('id')->pluck('id');

		return RowsFinderHelper::getCompositeRowCollectionByIds($ids)
			->filter(fn (ProductPackageBaseRowModel $row) => $row->getSellable())
			->values();
	}
}
