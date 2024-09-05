<?php

namespace IlBronza\Products\Models\Traits\Sellable;

use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Support\Collection;

trait InteractsWithSellableTrait
{
	public function getNameForSellable(...$parameters) : string
	{
		return $this->getName();
	}

	static public function getPossibleSellableElements() : Collection
	{
		return static::all();
	}

	public function sellables()
	{
		return $this->morphMany(
			Sellable::getProjectClassName(), 'target'
		);
	}

	public function getSellables() : Collection
	{
		return $this->sellables;
	}

	public function sellableSuppliers()
	{
		return $this->hasManyThrough(
			SellableSupplier::getProjectClassName(), Sellable::getProjectClassName(), 'target_id', 'sellable_id'
		)->where('target_type', $this->getMorphClass());
	}

	public function quotations()
	{
		return $this->belongsToMany(Quotation::getProjectClassName());
	}

	public function getRelatedQuotations() : Collection
	{
		$quotationsTable = config('products.models.quotation.table');
		$quotationrowsTable = config('products.models.quotationrow.table');

		return Quotation::getProjectClassName()::whereIn(
			"{$quotationsTable}.id", $this->quotationrows()->select("{$quotationrowsTable}.id")->pluck('id')
		)->with(
				'project', 'client', 'directPrice'
			)->withCount('quotationrows')->get();
	}

	public function quotationrows()
	{
		$sellableTable = Sellable::getProjectClassName()::make()->getTable();

		return $this->hasManyThrough(
			Quotationrow::getProjectClassName(), Sellable::getProjectClassName(), 'target_id', 'sellable_id'
		)->where($sellableTable . '.target_type', $this->getMorphClass());
	}

	public function getRelatedQuotationrows()
	{
		return $this->quotationrows()->with('quotation.project')->with('quotation.client')->with('directPrice')->with('sellableSupplier.directPrice')->with('sellableSupplier.sellable.target')->get();
	}

}