<?php

namespace IlBronza\Products\Models\Interfaces;

use IlBronza\Products\Models\Interfaces\SellableSupplierPriceCreatorBaseClass;
use IlBronza\Products\Models\Sellables\Supplier;
use Illuminate\Support\Collection;

interface SellableItemInterface
{
	/**
	 * returns all possible sellable elements from the given model
	 * 
	 * this is already declared in InteractsWithSellableTrait,
	 * ready to be overridden it in the model class
	 * 
	 * @return Collection
	 * 
	 **/
	public static function getPossibleSellableElements() : Collection;


	/**
	 * this is needed when you create bulk sellables itesm from a class
	 * 
	 * ex. vehicles: you take all the vehicles, then you get the owner
	 * then you create a sellable/supplier item to relate them
	 * 
	 * @return Collection
	 **/
	public function getPossibleSuppliersElements() : Collection;


	/**
	 * this method should return an hypothetic price value from a relation
	 * between a element and its potential supplier
	 * 
	 * @param Supplier $supplier
	 * @param mixed ...$parameters
	 * 
	 * @return array
	 * 
	 **/
	public function getSellablePricesBySupplier(Supplier $supplier, ...$parameters) : array;


	/**
	 * this methods return the helper class that must provide default prices
	 * 
	 * @return SellableSupplierPriceCreatorBaseClass
	 * 
	 **/
	public function getPriceCreator() : SellableSupplierPriceCreatorBaseClass;

	public function getNameForSellable(...$parameters) : string;
}