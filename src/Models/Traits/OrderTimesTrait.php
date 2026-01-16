<?php

namespace IlBronza\Products\Models\Traits;

trait OrderTimesTrait
{
	public function getLengthDaysAttribute() : int
	{
		if(! $minDate = $this->orderrows->min('starts_at'))
			return 0;

		if(! $maxDate = $this->orderrows->max('starts_at'))
			return 0;

		return abs(
			$maxDate->diffInWeekdays($minDate)
		);
	}	
}