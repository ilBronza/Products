<?php

namespace IlBronza\Products\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class StoredOrCalculatedExtraField implements CastsAttributes
{
	public function getCalculatedFieldName($key)
	{
    	return str_replace('calculated_', '', $key);
	}

    public function get($model, string $key, $value, array $attributes)
    {
        return $model->getCalculateOverrideablePriceValue(
        	$this->getCalculatedFieldName($key)
        );
    }

    public function set($model, string $key, $value, array $attributes)
    {
		return $model->setCalculateOverrideablePriceValue(
			$this->getCalculatedFieldName($key),
			$value
		);
    }
}
