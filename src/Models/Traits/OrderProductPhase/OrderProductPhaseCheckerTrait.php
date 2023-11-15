<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

trait OrderProductPhaseCheckerTrait
{
	public function hasWorkstation(mixed $workstationId)
	{
		if(is_array($workstationId))
			return in_array($this->getWorkstationId(), $workstationId);

		return $this->getWorkstationId() == $workstationId;
	}
}