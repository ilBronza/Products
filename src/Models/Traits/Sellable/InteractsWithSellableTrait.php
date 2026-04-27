<?php

namespace IlBronza\Products\Models\Traits\Sellable;

use IlBronza\Clients\Models\ClientAsSupplier;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;

trait InteractsWithSellableTrait
{
	abstract public function getPriceFieldsForSellable() : array;
	abstract public function getPossibleSuppliers() : Collection;
	abstract public function mustAutomaticallyUpdatePricesBySellable() : bool;

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
				$sellable = SellableCreatorHelper::getOrCreateSellableByTarget($model, null, $model->getSellableTypeName());

				$possibleSuppliers = $model->getPossibleSuppliers();

				foreach($possibleSuppliers as $possibleSupplier)
				{
					$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($possibleSupplier, $sellable);

					if($sellable->getTarget()?->mustAutomaticallyUpdatePricesBySellable())
						$sellableSupplier->updatePricesBySellableAndSupplier();
				}

				// dd($possibleSuppliers);

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

	public function sellable() : MorphOne
	{
		return $this->morphOne(Sellable::gpc(), 'target');
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

	public function getSellable(bool $force = false) : ? Sellable
	{
		if($force)
			return $this->sellable()->first();

		if($this->sellable)
			return $this->sellable;

		if(! $force)
			return null;

		$sellable = SellableCreatorHelper::getOrcreateSellableByTarget($this);

		$this->setRelation('sellable', $sellable);

		return $this->sellable;
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

	public function getPossibleSuppliersElements() : Collection
	{
		return ClientAsSupplier::gpc()::with('supplier')->get()->pluck('supplier')->filter();
	}

	public function getSellablePricesBySupplier(Supplier $supplier, ...$parameters) : array
	{
		throw new Exception('verificare');
	}

}