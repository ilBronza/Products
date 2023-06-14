<?php

namespace IlBronza\Products\Models\Traits\Product;

trait ProductQueriesTrait
{
	public function getLastCompletedOrderProductPhase(string $workstationId)
	{
		return $this->orderProductPhases()
			->byWorkstationId($workstationId)
			->sortByExtraField('completed_at', 'DESC')
			->first();
	}
}