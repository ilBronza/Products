<?php

namespace IlBronza\Products\Casts;

use IlBronza\Products\Casts\CalculatedTotalsExtraField;

class CalculatedTotalMarginExtraField extends CalculatedTotalsExtraField
{
	public function get($model, string $key, $value, array $attributes)
	{
		return $model->getMarginByCustomRows($this->relationRowName);
	}
}
