<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\CRUDSluggableTrait;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\Category\Traits\InteractsWithCategoryStandardMethodsTrait;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Products\Models\Interfaces\SellableItemInterface;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;
use Illuminate\Support\Collection;

class Sellable extends BaseModel
{
	use ProductPackageBaseModelTrait;
	use InteractsWithNotesTrait;
	use InteractsWithCategoryTrait;
	use InteractsWithCategoryStandardMethodsTrait;

	use CRUDUseUuidTrait;

	use CRUDParentingTrait;
	use CRUDSluggableTrait;

	static $deletingRelationships = [];
	static $restoringRelationships = [];

	static $modelConfigPrefix = 'sellable';


	public function target()
	{
		return $this->morphTo();
	}

	public function getTarget() : ? SellableItemInterface
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
			Quotation::getProjectClassName(),
			config('products.models.quotationrow.table')
		);		
	}

	public function sellableSuppliers()
	{
		return $this->hasMany(
			config('products.models.sellableSupplier.class')
		);
	}

	public function getSellableSuppliers() : Collection
	{
		return $this->sellableSuppliers;
	}


	public function suppliers()
	{
		return $this->belongsToMany(
			config('products.models.supplier.class'),
			config('products.models.sellableSupplier.table'),
			'sellable_id',
			'supplier_id'
		);
	}

	public function getFullrelatedSellableSupplierElements()
	{
		return $this->sellableSuppliers()->with(
			'supplier.target',
			'directPrice'
		)->get();
	}

	public function getFullrelatedSupplierElements()
	{
		return $this->suppliers()->with(
			'target',
			'categories',
			'sellables.target'
		)->get();
	}
}