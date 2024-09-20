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
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Models\Traits\ProductPackageBaseModelTrait;

use function round;

class Quotationrow extends ProductPackageBaseModel
{
	use CRUDUseUuidTrait;

	static $modelConfigPrefix = 'quotationrow';
	use InteractsWithPriceTrait;
	use InteractsWithFormTrait;
	use ProductPackageBaseModelTrait;
	use InteractsWithNotesTrait;
	use CRUDParentingTrait;

	protected $keyType = 'string';

	protected $casts = [
		'starts_at' => 'date',
		'ends_at' => 'date'
	];
	static $deletingRelationships = [];
	protected $with = ['sellable'];

	public function getStartsAt() : ?Carbon
	{
		return $this->starts_at ?? $this->getQuotation()->getStartsAt();
	}

	public function getQuotation() : ?Quotation
	{
		return $this->quotation;
	}

	public function setStartsAt(string|Carbon $startsAt = null) : void
	{
		$this->starts_at = $startsAt;
	}

	public function getEndsAt() : ?Carbon
	{
		return $this->ends_at ?? $this->getQuotation()->getEndsAt();
	}

	public function setEndsAt(string|Carbon $endsAt = null) : void
	{
		$this->ends_at = $endsAt;
	}

	public function sellableSupplier()
	{
		return $this->belongsTo(SellableSupplier::getProjectClassName());
	}

	public function getSupplier() : ?Supplier
	{
		return $this->getSellableSupplier()?->getSupplier();
	}

	public function getSellableSupplier() : ?SellableSupplier
	{
		return $this->sellableSupplier;
	}

	public function quotation()
	{
		return $this->belongsTo(Quotation::getProjectClassName());
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

	public function getCalculatedCostCompanyAttribute()
	{
		if ($value = $this->cost_company)
			return $value;

		if ($sellableSupplier = $this->getSellableSupplier())
			if ($price = $sellableSupplier->getPriceByCollectionId('costCompanyDay'))
				if ($value = $price->price)
					return $value;

		return $this->getSellable()->getCostCompany();
	}

	/**
	 * @return string
	 *
	 *
	 */
	public function getCalculatedCostCompanyHtmlClass() : string
	{
		if ($value = $this->cost_company)
			return 'costcompanyforced';

		if ($sellableSupplier = $this->getSellableSupplier())
			if ($price = $sellableSupplier->getPriceByCollectionId('costCompanyDay'))
				if ($value = $price->price)
					return 'costcompanysellsupp';

		return 'costcompanysell';
	}

	public function setCalculatedCostCompanyAttribute($value)
	{
		$this->cost_company = $value;
	}

	//	public function getCalculatedClientPriceAttribute()
	//	{
	//		if ($value = $this->client_price)
	//			return $value;
	//
	//		return $this->getSellable()->getClientPrice();
	//	}

	//	public function getClientPrice() : ?float
	//	{
	//		return $this->client_price;
	//	}

	//	public function getCalculatedClientPriceHtmlClass()
	//	{
	//		if ($value = $this->client_price)
	//			return 'clientpriceforced';
	//
	//		return 'clientpricecalculated';
	//	}

	public function setCalculatedClientPriceAttribute($value)
	{
		$this->client_price = $value;
	}

	public function getCalculatedCostCompanyTotalAttribute()
	{
		if ($value = $this->cost_company_total)
			return round($value, 2);

		return round($this->getQuantity() * $this->calculated_cost_company, 2);
	}

	public function getQuantity() : ?float
	{
		return $this->quantity;
	}

	public function getCalculatedKmAttribute()
	{
		if ($value = $this->km)
			return round($value, 2);

		return $this->getQuotation()->getKm();
	}

	public function getCalculatedCostCompanyTotal() : float
	{
		return $this->calculated_cost_company_total;
	}

	public function getCalculatedCostCompanyTotalHtmlClass()
	{
		if ($value = $this->cost_company_total)
			return 'costcompanytotalforced';

		return 'costcompanytotalcalculated';
	}

	public function setCalculatedCostCompanyTotalAttribute($value)
	{
		$this->cost_company_total = $value;
	}


	//	public function getCalculatedClientPriceTotalAttribute()
	//	{
	//		if ($value = $this->client_price_total)
	//			return $value;
	//
	//		return $this->getQuantity() * $this->calculated_client_price;
	//	}

	//	public function getCalculatedClientPriceTotalHtmlClass()
	//	{
	//		if ($value = $this->client_price_total)
	//			return 'clientpricetotalforced';
	//
	//		return 'clientpricetotalcalculated';
	//	}

	//	public function setCalculatedClientPriceTotalAttribute($value)
	//	{
	//		$this->client_price_total = $value;
	//	}

	public function getParameter(string $key, mixed $default = null) : mixed
	{
		$parameters = $this->getParameters();

		return $parameters[$key] ?? $default;
	}

	public function getParameters() : array
	{
		return $this->parameters ?? [];
	}

	public function setParameter(string $key, mixed $value = null)
	{
		$parameters = $this->getParameters();

		$parameters[$key] = $value;

		$this->parameters = $parameters;
		$this->save();
	}
}