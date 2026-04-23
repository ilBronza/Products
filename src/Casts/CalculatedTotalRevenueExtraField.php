<?php

namespace IlBronza\Products\Casts;

use IlBronza\Products\Casts\CalculatedTotalsExtraField;

class CalculatedTotalRevenueExtraField extends CalculatedTotalsExtraField
{
	public function get($model, string $key, $value, array $attributes)
	{
		return $model->getTotalByCustomRowsRevenue($this->relationRowName);
	}
}
