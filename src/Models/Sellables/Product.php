<?php

namespace IlBronza\Products\Models\Sellables;

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

	public function getDependentSellables() : array
	{
		return [
			'accessories',
			'accessoryTypes'
		];
	}
}