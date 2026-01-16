<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\Category\Models\Category;
use IlBronza\Contacts\Models\Traits\InteractsWithContact;
use IlBronza\CRUD\Interfaces\GanttTimelineInterface;
use IlBronza\CRUD\Traits\Timeline\GanttTimelineTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Payments\Models\Traits\InteractsWithPaymenttypes;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\Quotations\Quotationrow;
use Illuminate\Support\Collection;

use function app;
use function dd;
use function is_array;
use function json_encode;
use function request;

class Supplier extends ProductPackageBaseModel implements GanttTimelineInterface
{
	use GanttTimelineTrait;

	//	use InteractsWithCategoryTrait;
	use InteractsWithPaymenttypes;
	use InteractsWithContact;
	use InteractsWithFormTrait;

	static $deletingRelationships = [''];
	static $restoringRelationships = [''];

	static $modelConfigPrefix = 'supplier';

	static function getSupplierCategory() : Category
	{
		return Category::getProjectClassName()::findCachedField('name', 'Fornitore');
	}

	static function getInternalIds() : array
	{
		dd('estendere lista fornitori interni');
	}

	public function getCreateSellableSupplierUrl() : string
	{
		return $this->getKeyedRoute('createSellableSupplier');
	}

	public function getStoreSellableSupplierUrl() : string
	{
		return $this->getKeyedRoute('storeSellableSupplier');
	}
	
	public function getOrderrowsIndexUrl() : string
	{
		return $this->getKeyedRoute('orderrows.index');
	}

	public function getName() : ?string
	{
		return $this->getTarget()?->getName();
	}

	public function getNameAttribute() : ? string
	{
		return $this->getName();
	}

	public function getTarget() : ?SupplierInterface
	{
		if (is_array($this->target))
			return $this->target()->first();

		return $this->target;
	}

	public function target()
	{
		return $this->morphTo();
	}

	public function sellables()
	{
		return $this->belongsToMany(
			Sellable::getProjectClassName(), config('products.models.sellableSupplier.table')
		)->using(SellableSupplier::getProjectClassName());
	}

	public function getSellables() : Collection
	{
		return $this->sellables;
	}

	public function getRelatedQuotationrows() : Collection
	{
		return $this->quotationrows()->with(
			'directPrice', 'sellableSupplier.directPrice', 'sellable', 'quotation.client', 'quotation.project'
		)->get();
	}

	public function quotationrows()
	{
		return $this->hasManyThrough(
			Quotationrow::getProjectClassName(), SellableSupplier::getProjectClassName(), 'supplier_id', 'sellable_supplier_id'
		);
	}

	public function getSellableSuppliers() : Collection
	{
		return $this->sellableSuppliers()->with(
			'directPrice.measurementUnit', 'sellable.target', 'supplier.target'
		)->get();
	}

	public function sellableSuppliers()
	{
		return $this->hasMany(
			SellableSupplier::getProjectClassName(),
		);
	}

	public function getCategoriesCollection() : ?string
	{
		return null;
	}

	public function getCategoryModel() : string
	{
		return config('category.models.category.class');
	}

	public function getTargetString()
	{
		return json_encode($this->target->defaultDestination->address);
	}

	public function scopeByTargetType($query, string $targetType)
	{
		$query->where('target_type', $targetType);
	}

	public function getSellableSuppliersIds() : array
	{
		return $this->sellableSuppliers()->select('id')->pluck('id')->toArray();
	}

	public function getAssociateSupplierToSellableByOrderrowUrl()
	{
		return app('products')->route('orderrows.associateSupplierToSellable', [
			'orderrow' => request()->orderrow,
			'supplier' => $this->getKey()
		]);
	}

	public function getAssociateSupplierToSellableByQuotationrowUrl()
	{
		return app('products')->route('quotationrows.associateSupplierToSellable', [
			'quotationrow' => request()->quotationrow,
			'supplier' => $this->getKey()
		]);
	}

}