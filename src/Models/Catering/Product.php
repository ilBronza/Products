<?php

namespace IlBronza\Products\Models\Catering;

use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\Prices\Models\Interfaces\WithPriceInterface;
use IlBronza\Prices\Models\Traits\HasCustomPricesTrait;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Product\Product as IbProduct;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Models\Traits\Sellable\InteractsWithSellableTrait;
use IlBronza\Products\Providers\RelationshipsManagers\CateringProductRelationManager;
use Illuminate\Support\Collection;

class Product extends IbProduct implements SellableItemInterface, WithPriceInterface
{
	use HasCustomPricesTrait;
	use InteractsWithSellableTrait;
	use InteractsWithAllergensTrait;

	protected $casts = [
		'base_quantity_calculator' => ExtraField::class,
		'minimum_quantity' => ExtraField::class,
	];

	public function getRelationshipsManagerClass() : ?string
	{
		return CateringProductRelationManager::class;
	}

	public function getPriceFieldsForSellable() : array
	{
		return [
			'single_cost' => 'piece',
			'single_revenue' => 'piece',
		];
	}

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

	public function getAllergensListAttribute() : Collection
	{
		return $this->getAllergensList();
	}

	public function getAllergensList() : Collection
	{
		return cache()->remember(
			$this->cacheKey('getAllergensList'),
			3600,
			function()
			{
				return $this->_getAllergensList();
			}
		);
	}

	public function _getAllergensList() : Collection
	{
		$visitedProducts = [];

		return $this->getAllergensListFromDescendants($visitedProducts);
	}

	protected function getAllergensListFromDescendants(array &$visitedProducts) : Collection
	{
		$productKey = $this->getKey();
		$productIdentifier = static::class . ':' . (
			$productKey === null
				? 'object:' . spl_object_id($this)
				: 'key:' . $productKey
		);

		if (isset($visitedProducts[$productIdentifier]))
			return collect();

		$visitedProducts[$productIdentifier] = true;

		$result = $this->getAllergens();

		foreach($this->getDescendants() as $product)
			$result = $result->merge($product->getAllergensListFromDescendants($visitedProducts));

		return $result
			->unique(fn ($allergen) => $allergen->getKey())
			->values();
	}

	public function getAllergensListStringAttribute() : string
	{
		return $this->getCachedCalculatedProperty(
			'allergens_list_string',
			fn () : string => $this->getAllergensList()->pluck('name')->implode(' - ')
		);
	}

	public function getDependentSellables() : array
	{
		return [
			'accessories',
			'accessoryTypes'
		];
	}
}
