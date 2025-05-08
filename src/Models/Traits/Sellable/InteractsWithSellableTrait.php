<?php

namespace IlBronza\Products\Models\Traits\Sellable;

use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use Illuminate\Support\Collection;

use function app;
use function class_basename;
use function class_uses;
use function dd;
use function get_class_methods;

trait InteractsWithSellableTrait
{
	abstract public function getPriceFieldsForSellable() : array;

	protected static function bootInteractsWithSellableTrait()
	{
		\Event::listen('adjustPricesEvent', function ($model) {

			//if model use InteractsWithSellableTrait
			if (! in_array('IlBronza\Products\Models\Traits\Sellable\InteractsWithSellableTrait', class_uses_recursive($model)))
				return;

			SellableCreatorHelper::getOrCreateSellableByTarget($model, null, $model->getSellableTypeName());
		});

		//if this has CRUDModelExtraFieldsTrait
//		if (! in_array('IlBronza\CRUD\Traits\Model\CRUDModelExtraFieldsTrait', class_uses(static::class)))
			static::saved(function($model)
			{
				SellableCreatorHelper::getOrCreateSellableByTarget($model, null, $model->getSellableTypeName());
			});
	}

	public function getNameForSellable(...$parameters) : string
	{
		return $this->getName();
	}

	public function getSellableTypeName(...$parameters) : string
	{
		return class_basename($this);
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