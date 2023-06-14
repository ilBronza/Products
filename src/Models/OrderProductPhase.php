<?php

namespace IlBronza\Products\Models;

use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseRelationshipsTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseScopesTrait;

class OrderProductPhase extends ProductPackageBaseModel
{
	static $modelConfigPrefix = 'orderProductPhase';

	use OrderProductPhaseScopesTrait;
	use OrderProductPhaseRelationshipsTrait;

	public function getWorkstationId()
	{
		if($this->workstation_overridden_id)
			return $this->workstation_overridden_id;

		return $this->getPhase()->getWorkstationId();
	}
}