<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\CRUD\Traits\Timeline\GanttTimelineTrait;
use IlBronza\Category\Traits\InteractsWithCategoryStandardMethodsTrait;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Prices\Models\Interfaces\WithPriceInterface;
use IlBronza\Prices\Models\Traits\HasCustomPricesTrait;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Prices\Models\Traits\UpdatePricesOnSaveTrait;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;
use Illuminate\Support\Collection;
use function app;
use function array_filter;
use function config;
use function request;
use function trans;

class Sellable extends ProductPackageBaseModel implements WithPriceInterface
{
	use ProductPackageBaseModelTrait;
	use InteractsWithNotesTrait;
	use InteractsWithCategoryTrait;
	use InteractsWithCategoryStandardMethodsTrait;
	use GanttTimelineTrait;

	use CRUDUseUuidTrait;

	static $deletingRelationships = [];
	use CRUDParentingTrait;
	use CRUDSluggableTrait;

	use HasCustomPricesTrait;
	use UpdatePricesOnSaveTrait;

	use InteractsWithPriceTrait;

	static $restoringRelationships = [];
	static $modelConfigPrefix = 'sellable';
	protected $keyType = 'string';

	public function target()
	{
		return $this->morphTo();
	}

	public function getTarget() : ?SellableItemInterface
	{
		return $this->target;
	}

	public function quotationrows()
	{
		return $this->hasMany(
			Quotationrow::gpc()
		);
	}

	public function quotations()
	{
		return $this->belongsToMany(
			Quotation::gpc(), config('products.models.quotationrow.table')
		);
	}

	public function getSellableSuppliersRelatedElements() : Collection
	{
		return $this->sellableSuppliers()->with(
			'sellable', 'supplier.target', 'prices'
		)->get();
	}

	public function getQuotationsRelatedElements() : Collection
	{
		return $this->quotations()->with(
			'project', 'client'
		)->get();
	}

	public function getOrdersRelatedElements() : Collection
	{
		return $this->orders()->with(
			'project', 'client', 'destination.address', 'quotation', 'extraFields'
		)->get();
	}

	public function orders()
	{
		return $this->belongsToMany(
			Order::gpc(), config('products.models.orderrow.table')
		);
	}

	public function getSellableSuppliers() : Collection
	{
		return $this->sellableSuppliers;
	}

	public function suppliers()
	{
		return $this->belongsToMany(
			config('products.models.supplier.class'), config('products.models.sellableSupplier.table'), 'sellable_id', 'supplier_id'
		);
	}

	public function getFullrelatedSellableSupplierElements()
	{
		return $this->sellableSuppliers()->with(
			'supplier.target', 'directPrice'
		)->get();
	}

	public function sellableSuppliers()
	{
		return $this->hasMany(
			config('products.models.sellableSupplier.class')
		);
	}

	public function scopeByTargetType($query, string $targetType)
	{
		$query->where('target_type', $targetType);
	}

	public function scopeByType($query, string $type)
	{
		$query->where('type', $type);
	}

	public function scopeByTypes($query, array|Collection $types)
	{
		$query->whereIn('type', $types);
	}

	public function getType() : string
	{
		return $this->type;
	}

	public function scopeByTargetIds($query, array|Collection $targetIds)
	{
		$query->where('target_id', $targetIds);
	}


	public function getCostCompany() : float
	{
		if ($this->cost_company)
			return $this->cost_company;

		return $this->getTarget()?->getCostCompany() ?? 0;
	}

	//	public function getFullrelatedSupplierElements()
	//	{
	//		return $this->suppliers()->with(
	//			'target',
	//			'target.categories',
	//			'sellables.target'
	//		)->get();
	//	}

	public function getCreateSellableSupplierUrl() : string
	{
		return $this->getKeyedRoute('createSellableSupplier');
	}

	public function getStoreSellableSupplierUrl() : string
	{
		return $this->getKeyedRoute('storeSellableSupplier');
	}

	public function getPossibleTypeValuesArray() : array
	{
		$types = array_filter(
			array_merge(
				static::select('type')->distinct()->pluck('type')->toArray(), 
				config('products.models.sellable.availableTypes', [])
			)
		);

		$result = [];

		foreach ($types as $type)
			$result[$type] = trans('products::sellables.types.' . $type);

		return $result;
	}

	public function mustAutomaticallyUpdatePrices(): bool
	{
		return config('products.models.sellable.automaticUpdatesPrices');
	}

	public function getCategories() : Collection
	{
		return cache()->remember(
			$this->cacheKey('categories'),
			3600,
			function ()
			{
				return $this->getTarget()?->getCategories();				
			}
		);
	}

	public function getCategoriesListAttribute() : string
	{
		return cache()->remember(
			$this->cacheKey('cagetories_list_attribute'),
			3600 * 24,
			function()
			{
				return $this->getCategories()->pluck('name')->implode('<br />');
			}
		);
	}

}