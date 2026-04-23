<?php

namespace IlBronza\Products\Models\Catering;

use App\Models\ProjectSpecific\Allergen;
use App\Models\ProjectSpecific\Allergenizable;
use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\Prices\Models\Interfaces\WithPriceInterface;
use IlBronza\Prices\Models\Traits\HasCustomPricesTrait;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Product\Product as IbProduct;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSellableTrait;
use Illuminate\Support\Collection;

class Product extends IbProduct implements SellableItemInterface, WithPriceInterface
{
	use HasCustomPricesTrait;
	use InteractsWithSellableTrait;

	protected $casts = [
		'base_quantity_calculator' => ExtraField::class,
		'minimum_quantity' => ExtraField::class,
	];

	public function getPossibleSuppliers() : Collection
	{
		return collect([
			Supplier::gpc()::getOwnerSupplier()]
		);
	}

	public function mustAutomaticallyUpdatePrices() : ? bool
	{
		return true;
	}

	public function mustAutomaticallyUpdatePricesBySellable() : bool
	{
		return true;
	}

	public function getRowFieldsToStore() : array
	{
		return [
			'stored_single_revenue' => 'single_revenue',
			'stored_single_cost' => 'single_cost'
		];
	}

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