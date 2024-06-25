<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\Category\Models\Category;
use IlBronza\Category\Traits\InteractsWithCategoryTrait;
use IlBronza\Contacts\Models\Traits\InteractsWithContact;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Payments\Models\Traits\InteractsWithPaymenttypes;
use IlBronza\Products\Models\Interfaces\SupplierInterface;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Support\Collection;

class Supplier extends ProductPackageBaseModel
{
	use InteractsWithCategoryTrait;
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

	public function target()
	{
		return $this->morphTo();
	}

	public function getTarget() : ? SupplierInterface
	{
		return $this->target;
	}

	public function getName() : ? string
	{
		return $this->getTarget()->getName();
	}

	public function sellables()
	{
		return $this->belongsToMany(
			Sellable::getProjectClassName(),
			config('products.models.sellableSupplier.table')
		)->using(SellableSupplier::getProjectClassName());
	}

	public function getSellables() : Collection
	{
		return $this->sellables;
	}

	public function sellableSuppliers()
	{
		return $this->hasMany(
			SellableSupplier::getProjectClassName(),
		);
	}

    public function quotationrows()
    {
        return $this->hasManyThrough(
            Quotationrow::getProjectClassName(),
            SellableSupplier::getProjectClassName(),
            'supplier_id',
            'sellable_supplier_id'
        );
    }

    public function getRelatedQuotationrows() : Collection
    {
    	return $this->quotationrows()->with(
    		'directPrice',
    		'sellableSupplier.directPrice',
    		'sellable',
    		'quotation.client',
    		'quotation.project'
    	)->get();
    }

	public function getSellableSuppliers() : Collection
	{
		return $this->sellableSuppliers()->with(
			'directPrice.measurementUnit',
			'sellable.target',
			'supplier.target'
		)->get();
	}

	public function getCategoriesCollection() : ? string
	{
		return null;
	}

	public function getCategoryModel() : string
	{
		return config('category.models.category.class');
	}
	
}