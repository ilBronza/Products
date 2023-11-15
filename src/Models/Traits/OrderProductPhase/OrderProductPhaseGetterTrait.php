<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

trait OrderProductPhaseGetterTrait
{
	public function getOrderIdAttribute() : ? string
	{
		return $this->getOrder()?->getKey();
	}
}