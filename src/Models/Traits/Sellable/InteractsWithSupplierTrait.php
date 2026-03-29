<?php

namespace IlBronza\Products\Models\Traits\Sellable;

use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\Sellables\SellableCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SupplierCreatorHelper;
use IlBronza\Ukn\Ukn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Collega un modello fornitore (morph su products_suppliers) a Supplier, SellableSupplier, righe ordine/preventivo.
 * Scope withSupplierId + live_supplier_id: stessi criteri morph (target_id / target_type) del Supplier.
 */
trait InteractsWithSupplierTrait
{
	abstract public function getPossibleSellables() : Collection;

	public function getSellableSuppliers() : ? Collection
	{
		return $this->getSupplier()?->getSellableSuppliers();
	}

	public function isSupplier() : bool
	{
		return !! $this->getSupplier();
	}

	public function supplier() : MorphOne
	{
		return $this->morphOne(Supplier::gpc(), 'target');
	}

	public function getSupplier(bool $force = false) : Supplier
	{
		if($force)
			return $this->supplier()->first();

		if($this->supplier)
			return $this->supplier;

		dd('Supplier assente: definire creazione da target (vedi Operator / One TV).');

		$supplier = SupplierCreatorHelper::createSupplierFromTarget($this);

		$this->setRelation('supplier', $supplier);

		return $this->supplier;
	}

	public function scopeWithSupplierId($query)
	{
		$query->addSelect([
			'live_supplier_id' => Supplier::getProjectClassName()::select(
				'id'
			)->whereColumn('target_id', $this->getTable() . '.id')->where('target_type', $this->getMorphClass())->take(1)
		]);
	}

	public function sellableSuppliers()
	{
		return $this->hasMany(
			SellableSupplier::getProjectClassName(), 'supplier_id', 'live_supplier_id'
		);
	}

	public function scopeWithSellableSuppliers($query)
	{
		$query->withSupplierId();
		$query->with('sellableSuppliers');
	}

	public function getSellableSuppliersBySupplier()
	{
		return $this->getSupplier()->getSellableSuppliers();
	}

	public function sellables()
	{
		return $this->belongsToMany(
			Sellable::getProjectClassName(), config('products.models.sellableSupplier.table'), 'supplier_id', null, 'live_supplier_id'
		)->using(SellableSupplier::getProjectClassName());
	}

	public function scopeWithSellables($query)
	{
		$query->withSupplierId();
		$query->with('sellables');
	}

	public function getSellablesBySupplier() : Collection
	{
		return $this->getSupplier()->getSellables();
	}

	public function getQuotationsBySupplier() : Collection
	{
		return $this->getSupplier()->getQuotations();
	}

	public function getSellableSuppliersIds() : Collection
	{
		if(! $supplier = $this->getSupplier())
			return collect();

		return SellableSupplier::gpc()::select('id')->where('supplier_id', $supplier->getKey())->get();
	}

	public function getOrderrowsForShowRelation() : Collection
	{
		return Orderrow::gpc()::with('order.client', 'order.project', 'sellable.target', 'extraFields')->whereIn('sellable_supplier_id', $this->getSellableSuppliersIds())->get();
	}

	public function getQuotationrowsForShowRelation() : Collection
	{
		return Quotationrow::gpc()::with('quotation.client', 'quotation.project', 'sellable.target', 'extraFields')->whereIn('sellable_supplier_id', $this->getSellableSuppliersIds())->get();
	}

	protected static function bootInteractsWithSupplierTrait()
	{
		static::saved(function ($model)
		{
			$supplier = SupplierCreatorHelper::getOrCreateSupplierFromTarget($model);

			$possibleSellables = $model->getPossibleSellables();

			foreach($possibleSellables as $possibleSellable)
				$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $possibleSellable);
		});

		static::deleting(function ($model)
		{
			if($supplier = $model->getSupplier())
				$supplier->delete();
		});
	}

	public function getQuotationrowsBySupplierRelationsManagerController() : string
	{
		return config('products.models.quotationrow.controllers.index');		
	}

	public function getOrderrowsBySupplierRelationsManagerController() : string
	{
		return config('products.models.orderrow.controllers.index');		
	}

	public function getSellableSuppliersBySupplierRelationsManagerController() : string
	{
		return config('products.models.sellableSupplier.controllers.index');
	}

	public function eagerLoadSellableSuppliersRelation(string $type = null) : Collection
	{
		if(($this->sellableSuppliers ?? false)&&(count($this->sellableSuppliers)))
			return $this->sellableSuppliers;

		if($type)
			$sellableSuppliers = $this->getSupplier()?->getSellableSuppliersByType($type);
		else
			$sellableSuppliers = $this->getSupplier()?->getSellableSuppliers();

		$this->setRelation('sellableSuppliers', $sellableSuppliers);

		return $sellableSuppliers;
	}

	public function eagerLoadOrderrowsRelation(string $type = null) : Collection
	{
		if(($this->orderrows ?? false)&&(count($this->orderrows)))
			return $this->orderrows;

		if($type)
			$orderrows = $this->getSupplier()?->getOrderrowsByType($type);
		else
			$orderrows = $this->getSupplier()?->getOrderrows();

		$this->setRelation('orderrows', $orderrows);

		return $orderrows;
	}

	public function eagerLoadQuotationrowsRelation(string $type = null) : Collection
	{
		if(($this->quotationrows ?? false)&&(count($this->quotationrows)))
			return $this->quotationrows;

		if($type)
			$quotationrows = $this->getSupplier()?->getQuotationrowsByType($type);
		else
			$quotationrows = $this->getSupplier()?->getQuotationrows();

		$this->setRelation('quotationrows', $quotationrows);

		return $quotationrows;
	}

	public function getQuotationrowsBySupplierRelationsManagerFieldsGroupsParametersFile(string $type = null) : string
	{
		if(! $type)
			$type = $this->getModelConfigPrefix();

		$key = static::getPackageConfigPrefix() . '.models.quotationrow.fieldsGroupsFiles.index';
		if(! $result = config($key))
			dd($key);

		return $result;
	}

	public function getOrderrowsBySupplierRelationsManagerFieldsGroupsParametersFile(string $type = null) : string
	{
		if(! $type)
			$type = $this->getModelConfigPrefix();

		$key = static::getPackageConfigPrefix() . '.models.orderrow.fieldsGroupsFiles.index';

		if(! $result = config($key))
			dd($key);

		return $result;
	}

	public function getSellableSuppliersBySupplierRelationsManagerFieldsGroupsParametersFile(string $type = null) : string
	{
		if(! $type)
			$type = $this->getModelConfigPrefix();

		if(! $result = config("products.models.sellableSupplier.fieldsGroupsFiles.relatedBySupplier.{$type}"))
			dd("products.models.sellableSupplier.fieldsGroupsFiles.relatedBySupplier.{$type}");

		return $result;
	}

	public function getSellableSuppliersBySupplierRelationsManagerParameters(string $type = null)
	{
		$this->eagerLoadSellableSuppliersRelation($type);

		return [
			'controller' => $this->getSellableSuppliersBySupplierRelationsManagerController(),
			'fieldsGroupsParametersFile' => $this->getSellableSuppliersBySupplierRelationsManagerFieldsGroupsParametersFile($type),
		];
	}

	public function getQuotationrowsBySupplierRelationsManagerParameters(string $type = null)
	{
		$this->eagerLoadQuotationrowsRelation($type);

		$todo = trans('products::models.relationsManagerRelatedModelTodo');
		Log::info($todo);
		if(\Auth::id() == 1)
			Ukn::w($todo);

		return [
			'controller' => $this->getQuotationrowsBySupplierRelationsManagerController(),
			'fieldsGroupsParametersFile' => $this->getQuotationrowsBySupplierRelationsManagerFieldsGroupsParametersFile($type),
			'relationType' => 'HasMany',
			'relatedModelClass' => Quotationrow::gpc(),
			'relatedModel' => Quotationrow::gpc()::make(),
			'translatedTitle' => trans('products::models.productQuotationrowsBySellable'),
		];		
	}

	public function getOrderrowsBySupplierRelationsManagerParameters(string $type = null)
	{
		$this->eagerLoadOrderrowsRelation($type);

		$todo = trans('products::models.relationsManagerRelatedModelTodo');
		Log::info($todo);
		if(\Auth::id() == 1)
			Ukn::w($todo);

		return [
			'controller' => $this->getOrderrowsBySupplierRelationsManagerController(),
			'fieldsGroupsParametersFile' => $this->getOrderrowsBySupplierRelationsManagerFieldsGroupsParametersFile($type),
			'relationType' => 'HasMany',
			'relatedModelClass' => Orderrow::gpc(),
			'relatedModel' => Orderrow::gpc()::make(),
			'translatedTitle' => trans('products::models.productOrderrowsBySellable'),
		];		
	}
}