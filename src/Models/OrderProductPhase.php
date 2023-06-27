<?php

namespace IlBronza\Products\Models;

use IlBronza\Products\Models\Traits\CompletionScopesTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseRelationshipsTrait;
use IlBronza\Products\Models\Traits\OrderProductPhase\OrderProductPhaseScopesTrait;

class OrderProductPhase extends ProductPackageBaseModel
{
	static $modelConfigPrefix = 'orderProductPhase';

	use OrderProductPhaseScopesTrait;
	use OrderProductPhaseRelationshipsTrait;

	use CompletionScopesTrait;

	public function getWorkstationId()
	{
		if($this->workstation_overridden_id)
			return $this->workstation_overridden_id;

		return $this->getPhase()->getWorkstationId();
	}

	public function getQuantityRequired()
	{
		return $this->quantity_required;
	}

	public function getQuantityDone()
	{
		return $this->quantity_done;
	}

	public function setQuantityDone(float $quantityDone, bool $save = false)
	{
		$this->quantity_done = $quantityDone;

		if($save)
			$this->save();
	}













	//DA SISTEMARE CON QUERY

	public function getOrderId()
	{
		return $this->getOrderProduct()->order_id;
	}
}