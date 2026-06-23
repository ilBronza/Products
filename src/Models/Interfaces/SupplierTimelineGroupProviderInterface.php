<?php

namespace IlBronza\Products\Models\Interfaces;

use IlBronza\Timeline\Interfaces\TimelineGroupInterface;

interface SupplierTimelineGroupProviderInterface
{
	public function getSupplierTimelineGroup() : ?TimelineGroupInterface;
}
