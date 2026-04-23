<?php

namespace IlBronza\Products\Models\Traits\Sellable;

use IlBronza\CRUD\Models\Casts\CastFieldPrice;
use Illuminate\Database\Eloquent\Relations\Relation;

trait SellableSupplierPricesTrait
{
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

		if (! $sellableClass)
		{
			if(! $sellableTarget = $this->getSellable()->getTarget());
				return ;

			$sellableTargetClass = class_basename($sellableTarget);

			static::query()
				->whereKey($this->getKey())
				->update([
					'sellable_class' => $sellableTargetClass
				]);

			$this->sellable_class = $sellableTargetClass;
		}

		$fullClass = Relation::getMorphedModel($sellableClass);

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