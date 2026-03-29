<?php

namespace IlBronza\Products\Models\Catering;

use App\Models\ProjectSpecific\Allergen;
use App\Models\ProjectSpecific\Allergenizable;
use IlBronza\Products\Models\Product\Product as IbProduct;
use Illuminate\Support\Collection;

class Product extends IbProduct
{
	public function getServedAtTable() : bool
	{
		return !! $this->served_at_table;
	}

	public function getAllergensList() : Collection
	{
		$result = $this->getAllergens();

		foreach($this->getProducts() as $product)
			$result = $result->merge($product->getAllergensList());

		return $result->unique();
	}

	public function allergens()
	{
		return $this->morphToMany(
			Allergen::class,
			'categorizable',
			'project_allergenables',
		)->using(Allergenizable::class);
	}

	public function getAllergens() : Collection
	{
		return $this->allergens;
	}
}