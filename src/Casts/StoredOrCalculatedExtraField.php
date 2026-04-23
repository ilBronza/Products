<?php

namespace IlBronza\Products\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class StoredOrCalculatedExtraField implements CastsAttributes
{
	public function getFieldBasename($key)
	{
    	return str_replace('calculated_', '', $key);
	}

    public function get($model, string $key, $value, array $attributes)
    {
        return $model->getCalculateOverrideablePriceValue(
        	$this->getFieldBasename($key)
        );
    }

    public function set($model, string $key, $value, array $attributes)
    {
		return $model->setCalculateOverrideablePriceValue(
			$this->getFieldBasename($key),
			$value
		);
    }
}
