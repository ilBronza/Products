<?php

namespace IlBronza\Products\Casts;

use IlBronza\Products\Casts\CalculatedTotalsExtraField;

class CalculatedTotalPercentageMarginExtraField extends CalculatedTotalsExtraField
{
	public function get($model, string $key, $value, array $attributes)
	{
		return $model->getPercentageMarginByCustomRows($this->relationRowName);
	}
}
