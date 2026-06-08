<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

trait ExclusiveDiscountFieldsTrait
{
	public function formatMoney(mixed $value) : string
	{
		return number_format((float) $value, 2, ',', '.') . ' €';
	}

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

	public function hasDiscount() : bool
	{
		return !! $this->extraFields->discount_neat || !! $this->extraFields->discount_percentage;
	}

	public function getPdfTotalDiscountedString() : string
	{
		$fullPrice = (float) $this->getCalculatedTotalRowRevenueBeforeDiscount();
		$discount = (float) $this->getNeatDiscount($fullPrice);

		$finalPrice = (float) $this->getCalculatedTotalRowRevenue();

		if ($discount <= 0) {
			return $this->formatMoney($finalPrice);
		}

		return '<span style="text-decoration: line-through;">' . $this->formatMoney($fullPrice) . '</span>'
			. '<br />'
			. '<span style="color:red;">-' . $this->formatMoney($discount) . '</span>'
			. '<br />'
			. $this->formatMoney($finalPrice);
	}
}
