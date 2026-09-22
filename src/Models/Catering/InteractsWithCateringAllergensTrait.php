<?php

namespace IlBronza\Products\Models\Catering;

use Illuminate\Support\Collection;

trait InteractsWithCateringAllergensTrait
{
	public function getAllergensList() : Collection
	{
		return cache()->remember(
			$this->cacheKey('getAllergensList'),
			3600,
			fn () => $this->_getAllergensList()
		);
	}

	public function getAllergenListAttribute() : Collection
	{
		return $this->getAllergensList();
	}

	protected function _getAllergensList() : Collection
	{
		$result = collect();

		foreach ($this->getProductRows() as $productRow)
			if($target = $productRow->getSellable()?->getTarget())
				$result = $result->merge($target->getAllergensList());

		return $result
			->groupBy(fn ($allergen) => $allergen->getKey())
			->map(function (Collection $allergens)
			{
				$allergen = $allergens->first();
				$allergen->setAttribute('products_in_order_count', $allergens->count());

				return $allergen;
			})
			->values();
	}
}
