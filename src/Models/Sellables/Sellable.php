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

	public function getCostCompany() : float
	{
		if($this->cost_company)
			return $this->cost_company;

		return $this->getTarget()->getCostCompany();
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
}