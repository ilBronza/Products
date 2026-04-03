<?php

namespace IlBronza\Products\Models\Traits\Sellable;

trait SellableSupplierPricesTrait
{
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

		$helperclass = config($configString);

		$helper = new $helperclass($this);

		return $helper->updatePrices();
	}
}