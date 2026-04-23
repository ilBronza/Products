<?php

namespace IlBronza\Products\Casts;

use IlBronza\Products\Casts\CalculatedTotalsExtraField;

class CalculatedTotalCostExtraField extends CalculatedTotalsExtraField
{
	public function get($model, string $key, $value, array $attributes)
	{
		return $model->getTotalByCustomRowsCost($this->relationRowName);
	}
}
