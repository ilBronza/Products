<?php

namespace IlBronza\Products\Models\Traits\OrderProductPhase;

use App\Providers\Helpers\Timings\TimingEstimator;

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
		if(! $timingEstimation = $this->getTimingEstimation())
		{
			TimingEstimator::calculate($this);

			return $this->getTimingEstimation()?->getSeconds() / 60;
		}

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