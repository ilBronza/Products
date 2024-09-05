<?php

namespace IlBronza\Products\Models\Quotations;

use Carbon\Carbon;
use IlBronza\Category\Models\Category;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Notes\Traits\InteractsWithNotesTrait;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Models\ProductPackageBaseModel;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

use function method_exists;

class Quotationrow extends ProductPackageBaseModel
{
	use CRUDUseUuidTrait;

	static $modelConfigPrefix = 'quotationrow';
	use InteractsWithPriceTrait;
	use InteractsWithFormTrait;
	use ProductPackageBaseModelTrait;
	use InteractsWithNotesTrait;
	use CRUDParentingTrait;

	public function getStartsAt() : ? Carbon
	{
		return $this->starts_at ?? $this->getQuotation()->getStartsAt();
	}

	public function setStartsAt(string|Carbon $startsAt = null) : void
	{
		$this->starts_at = $startsAt;
	}

	public function getEndsAt() : ? Carbon
	{
		return $this->ends_at ?? $this->getQuotation()->getEndsAt();
	}

	public function setEndsAt(string|Carbon $endsAt = null) : void
	{
		$this->ends_at = $endsAt;
	}

	protected $keyType = 'string';
	protected $casts = [
		'starts_at' => 'date',
		'ends_at' => 'date'
	];

	protected $deletingRelationships = [];

	protected $with = ['sellable'];

	public function sellableSupplier()
	{
		return $this->belongsTo(SellableSupplier::getProjectClassName());
	}

	public function getSellableSupplier() : ? SellableSupplier
	{
		return $this->sellableSupplier;
	}

	public function quotation()
	{
		return $this->belongsTo(Quotation::getProjectClassName());
	}

	public function getQuotation() : Quotation
	{
		return $this->quotation;
	}

	public function getName() : ?string
	{
		return $this->getSellable()?->getName();
	}

	public function getSellable() : ?Sellable
	{
		if ($this->relationLoaded('sellable'))
			return $this->sellable;

		return $this->sellable()->first();
	}

	public function sellable()
	{
		return $this->belongsTo(Sellable::getProjectClassName());
	}

	public function scopeBySellableType($query, string $sellableClassname)
	{
		return $query->whereHas('sellable', function ($query) use ($sellableClassname)
		{
			$query->where('target_type', $sellableClassname);
		});
	}

	public function scopeBySellableCategory($query, string|Category $category)
	{
		if (is_string($category))
			$category = Category::getProjectClassName()::findCachedField('name', $category);

		return $query->whereHas('sellable', function ($query) use ($category)
		{
			$query->where('category_id', $category->id);
		});
	}

	public function getAssignSellablesupplierUrl()
	{
		return $this->getKeyedRoute('assignSellableSupplier');
	}

	public function getQuantity() : ? float
	{
		return $this->quantity;
	}

	public function getClientPrice() : ? float
	{
		return $this->client_price;
	}

	public function getCalculatedCostCompanyAttribute()
	{
		if($value = $this->cost_company)
			return $value;

		if($sellableSupplier = $this->getSellableSupplier())
			if($sellableSupplier->cost_company_day)
				return $sellableSupplier->cost_company_day;

		return $this->getSellable()->getCostCompany();
	}

	public function setCalculatedCostCompanyAttribute($value)
	{
		$this->cost_company = $value;
	}

	public function getCalculatedClientPriceAttribute()
	{
		if($value = $this->client_price)
			return $value;

		if($sellableSupplier = $this->getSellableSupplier())
			if(method_exists($sellableSupplier, 'getClientPrice'))
				return $sellableSupplier->getClientPrice();

		return $this->getSellable()->getClientPrice();
	}

	public function setCalculatedClientPriceAttribute($value)
	{
		$this->client_price = $value;
	}

	public function getCalculatedCostCompanyTotalAttribute()
	{
		if($value = $this->cost_company_total)
			return $value;

		return $this->getQuantity() * $this->calculated_cost_company;
	}

	public function setCalculatedCostCompanyTotalAttribute($value)
	{
		$this->cost_company_total = $value;
	}

	public function getCalculatedClientPriceTotalAttribute()
	{
		if($value = $this->client_price_total)
			return $value;

		return $this->getQuantity() * $this->calculated_client_price;
	}

	public function setCalculatedClientPriceTotalAttribute($value)
	{
		$this->client_price_total = $value;
	}

}