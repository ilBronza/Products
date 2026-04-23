<?php

namespace IlBronza\Products\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

abstract class CalculatedTotalsExtraField implements CastsAttributes
{
    public $relationRowName;

    public function __construct(string $relationRowName = null)
    {
        $this->relationRowName = $relationRowName;
    }

    abstract public function get(Model $model, string $key, mixed $value, array $attributes);

	public function set($model, string $key, $value, array $attributes)
	{
		dd('impossibile setare questo campo');
	}
}
