<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\Category\Traits\InteractsWithCategoryStandardMethodsTrait;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;
use Illuminate\Support\Collection;

use function array_filter;
use function json_encode;
use function trans;

class Sellable extends ProductPackageBaseModel
{
	use ProductPackageBaseModelTrait;
	use InteractsWithNotesTrait;
	use InteractsWithCategoryTrait;
	use InteractsWithCategoryStandardMethodsTrait;

	use CRUDUseUuidTrait;

	static $deletingRelationships = [];
	use CRUDParentingTrait;
	use CRUDSluggableTrait;

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
			Quotationrow::getProjectClassName()
		);
	}

	public function quotations()
	{
		return $this->belongsToMany(
			Quotation::getProjectClassName(), config('products.models.quotationrow.table')
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

	public function scopeByTargetIds($query, array|Collection $targetIds)
	{
		$query->where('target_id', $targetIds);
	}

	public function getCostCompany() : float
	{
		if($this->cost_company)
			return $this->cost_company;

		return $this->getTarget()?->getCostCompany() ?? 0;
	}

	public function getClientPrice()
	{
		return $this->getCostCompany() * 1.25;
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
		$types = static::select('type')->distinct()->pluck('type')->toArray();

		$types = array_filter($types);

		$result = [];

		foreach ($types as $type)
			$result[$type] = trans('products:sellables.types.' . $type);

		return $result;
	}

}