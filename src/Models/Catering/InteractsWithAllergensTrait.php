<?php

namespace IlBronza\Products\Models\Catering;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait InteractsWithAllergensTrait
{
	public function allergens() : MorphToMany
	{
		return $this->morphToMany(
			Allergen::gpc(),
			'allergenable',
			config('products.models.allergenable.table')
		)->using(Allergenable::gpc())
			->withTimestamps();
	}

	public function getAllergens() : Collection
	{
		return $this->allergens;
	}
}
