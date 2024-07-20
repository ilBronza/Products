<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

trait OrderProductPhaseGetterTrait
{
	public function getOrderIdAttribute() : ? string
	{
		return $this->getOrder()?->getKey();
	}

	public function getTimingEstimator()
	{
		$class = config('products.models.orderProductPhase.timingEstimator');

		return new $class($this);
	}

	public function getEstimatedTimeSecondsAttribute($value)
	{
		return $this->getTimingEstimator()->getEstimatedTimeSeconds();
	}

	public function getEstimatedTimeSecondsDebugResults()
	{
		return $this->getTimingEstimator()->getEstimatedTimeSecondsDebugResults();		
	}
}