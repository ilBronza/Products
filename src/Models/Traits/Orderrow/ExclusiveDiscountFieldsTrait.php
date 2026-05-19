<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

trait ExclusiveDiscountFieldsTrait
{
	public function getNeatDiscount(float $basePrice) : float
	{
		if($value = $this->extraFields->discount_neat)
			return $value;

		if(! $value = $this->extraFields->discount_percentage)
			return 0;

		return $basePrice / 100 * $value;
	}

	public function setDiscountNeatAttribute($value) : void
	{
		if ($this->shouldClearOppositeDiscountField($value))
			$this->extraFields->discount_percentage = null;

		$this->extraFields->discount_neat = $value;
	}

	public function setDiscountPercentageAttribute($value) : void
	{
		if ($this->shouldClearOppositeDiscountField($value))
			$this->extraFields->discount_neat = null;

		$this->extraFields->discount_percentage = $value;
	}

	protected function shouldClearOppositeDiscountField(mixed $value) : bool
	{
		if ($value === null || $value === '')
			return false;

		return true;
	}

}
