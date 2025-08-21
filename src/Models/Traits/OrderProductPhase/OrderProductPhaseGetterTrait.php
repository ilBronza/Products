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

	public function getEstimatedTimeMinutesAttribute($value) : float
	{
		return $this->getTimingEstimation()?->getSeconds() / 60;
	}

	public function getEstimatedMinutes() : float
	{
		return $this->getEstimatedTimeMinutes();
	}

	public function getEstimatedTimeMinutes() : float
	{
		return $this->estimated_time_minutes;
	}

//	public function getEstimatedTimeSecondsDebugResults()
//	{
//		return $this->getTimingEstimator()->getEstimatedTimeSecondsDebugResults();
//	}
}