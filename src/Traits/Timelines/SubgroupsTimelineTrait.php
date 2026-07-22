<?php

namespace IlBronza\Products\Traits\Timelines;

use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Timeline\Helpers\TimelineGroupCreatorHelper;
use IlBronza\Timeline\Interfaces\TimelineGroupInterface;
use Illuminate\Support\Collection;

/**
 * Timeline con i figli annidati sotto il gruppo padre.
 * Chi lo usa dichiara il getter delle righe che costruisce il gruppo
 * composito, i padri da mostrare e le righe da considerare.
 */
trait SubgroupsTimelineTrait
{
	public string $option = 'subgroups';

	abstract public function getSubgroupGetterMethod() : string;

	abstract protected function getParentTimelineGroups() : Collection;

	abstract protected function getSubgroupsTimelineItems() : Collection;

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
		//gli id dei nestedGroups, quelli dei gruppi figli e quelli dei
		//gruppi degli item vengono dalla stessa chiamata
		$subgroups = $rows
			->map(fn (ProductPackageBaseRowModel $row) => $row->{$getter}())
			->unique(fn (TimelineGroupInterface $subgroup) => $subgroup->getTimelineGroupId())
			->values();

		$subgroupIdsByParent = $subgroups
			->groupBy(fn (TimelineGroupInterface $subgroup) => $subgroup->getParent()->getTimelineGroupId())
			->map(fn (Collection $parentSubgroups) => $parentSubgroups
				->map(fn (TimelineGroupInterface $subgroup) => $subgroup->getTimelineGroupId())
				->values()
				->all()
			);

		foreach($this->getParentTimelineGroups() as $parent)
		{
			$group = TimelineGroupCreatorHelper::createGroupByModel($parent);
			$nestedGroups = $subgroupIdsByParent->get($parent->getTimelineGroupId(), []);

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
}
