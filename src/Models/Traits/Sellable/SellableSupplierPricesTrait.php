<?php

namespace IlBronza\Products\Models\Traits\Sellable;

use IlBronza\CRUD\Models\Casts\CastFieldPrice;
use IlBronza\CRUD\ResourceFilters\ResourceFilters;
use Illuminate\Database\Eloquent\Relations\Relation;

trait SellableSupplierPricesTrait
{
	public const SELLABLE_CLASS_NONE = '0';

	protected static function bootSellableSupplierPricesTrait() : void
	{
	    static::retrieved(function ($model)
	    {
	        $model->initializeDynamicSellableCasts();
	    });
	}


	public function initializeDynamicSellableCasts() : void
	{
		if(! $this->exists)
			return ;

		$sellableClass = $this->sellable_class;

		if ($sellableClass === static::SELLABLE_CLASS_NONE)
			return ;

		if ($sellableClass === null)
		{
			$sellableTarget = $this->getSellable()?->getTarget();
			$sellableClass = $sellableTarget ? class_basename($sellableTarget) : static::SELLABLE_CLASS_NONE;
			// A consultation scope can hide an existing target. Never persist
			// a class inferred from that partial view of the relationship.
			if (! app(ResourceFilters::class)->active()) {
				static::query()->whereKey($this->getKey())->whereNull('sellable_class')->update([
					'sellable_class' => $sellableClass,
				]);
				if ($sellableTarget)
					$this->sellable_class = $sellableClass;
			}
			if (! $sellableTarget)
				return;
		}

		$fullClass = Relation::getMorphedModel($sellableClass);

		// if (! $fullClass)
		// 	return ;

		$prices = $fullClass::gpc()::make()->getPriceFieldsForSellable();

		$casts = [];

		foreach ($prices as $field => $measurementUnit)
			$casts[$field] = CastFieldPrice::class . ":{$field},$measurementUnit";

		$this->mergeCasts(
			$casts
		);
	}

	/**
	 * 
	 * questa cerca un helper per fare l'aggiornamento dei prezzi sulla base di sellable e di supplier
	 * 
	 **/
	public function updatePricesBySellableAndSupplier()
	{
		$sellableTarget = $this->getSellable()->getTarget();
		$supplierTarget = $this->getSupplier()->getTarget();

		if(($sellableConfig = $sellableTarget->getPackageConfigPrefix()) == ($supplierConfig = $supplierTarget->getPackageConfigPrefix()))
			$configPrefix = $sellableConfig;
		else
			$configPrefix = 'products';

		$configString = "{$configPrefix}.sellableSupplierPricesHelper.{$sellableTarget->getModelConfigPrefix()}_{$supplierTarget->getModelConfigPrefix()}";

		$helperclass = cconfig($configString);

		$helper = new $helperclass($this);

		return $helper->updatePrices();
	}
}