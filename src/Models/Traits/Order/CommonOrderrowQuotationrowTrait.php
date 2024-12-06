<?php

namespace IlBronza\Products\Models\Traits\Order;

use Carbon\Carbon;
use IlBronza\Category\Models\Category;
use IlBronza\Clients\Models\Destination;
use IlBronza\CRUD\Traits\Model\CRUDParentingTrait;
use IlBronza\CRUD\Traits\Model\CRUDReorderableStandardTrait;
use IlBronza\FileCabinet\Traits\InteractsWithFormTrait;
use IlBronza\Prices\Models\Traits\InteractsWithPriceTrait;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;

use function is_string;
use function round;

trait CommonOrderrowQuotationrowTrait
{
	use CRUDParentingTrait;
	use InteractsWithPriceTrait;
	use InteractsWithFormTrait;
	use CRUDReorderableStandardTrait;

	public function setStartsAt(string|Carbon $startsAt = null) : void
	{
		$this->starts_at = $startsAt;
	}

	public function setEndsAt(string|Carbon $endsAt = null) : void
	{
		$this->ends_at = $endsAt;
	}

	public function getHasDifferentStartsAt()
	{
		if ($this->getStartsAt() != $this->getModelContainer()->getStartsAt())
			return 'differentstart';

		return null;
	}

	public function getStartsAt() : ?Carbon
	{
		return $this->starts_at;
	}

	public function getHasDifferentEndsAt()
	{
		if ($this->getEndsAt() != $this->getModelContainer()->getEndsAt())
			return 'differentend';

		return null;
	}

	public function getEndsAt() : ?Carbon
	{
		return $this->ends_at ?? $this->getModelContainer()->getEndsAt();
	}

	public function getStartsAtAttribute($value)
	{
		if ($value)
			return Carbon::createFromFormat('Y-m-d H:i:s', $value);

		return $this->getModelContainer()?->getStartsAt();
	}

	public function getEndsAtAttribute($value)
	{
		if ($value)
			return Carbon::createFromFormat('Y-m-d H:i:s', $value);

		return $this->getModelContainer()?->getEndsAt();
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

	public function scopeBySellableTargetIds($query, array|Collection $sellableIds)
	{
		return $query->whereHas('sellable', function ($query) use ($sellableIds)
		{
			$query->where('target_id', $sellableIds);
		});
	}

	public function scopeBySellableTargetType($query, string $sellableClassname)
	{
		return $query->whereHas('sellable', function ($query) use ($sellableClassname)
		{
			$query->where('target_type', $sellableClassname);
		});
	}

	public function scopeBySellableType($query, string $sellableType)
	{
		return $query->whereHas('sellable', function ($query) use ($sellableType)
		{
			$query->where('type', $sellableType);
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

	public function getFullname() : string
	{
		return $this->getSellable()?->getName() . ' ' . $this->getModelContainer()?->getName();
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

	public function getCalculatedRowtotal() : ?float
	{
		return $this->calculated_row_total;
	}

	public function setParameter(string $key, mixed $value = null)
	{
		$parameters = $this->getParameters();

		$parameters[$key] = $value;

		$this->parameters = $parameters;
		$this->save();
	}

	public function getParameters() : array
	{
		return $this->parameters ?? [];
	}

	public function getParameter(string $key, mixed $default = null) : mixed
	{
		$parameters = $this->getParameters();

		return $parameters[$key] ?? $default;
	}

	public function setCalculatedCostCompanyTotalAttribute($value)
	{
		$this->cost_company_total = $value;
	}

	public function getCalculatedCostCompanyTotalHtmlClass()
	{
		if ($value = $this->cost_company_total)
			return 'costcompanytotalforced';

		return 'costcompanytotalcalculated';
	}

	public function getCalculatedCostCompanyTotal() : float
	{
		return $this->calculated_cost_company_total;
	}

	public function getCalculatedKmAttribute()
	{
		if ($value = $this->km)
			return round($value, 2);

		return $this->getModelContainer()->getKm();
	}

	public function setCalculatedClientPriceAttribute($value)
	{
		$this->client_price = $value;
	}

	public function getCalculatedCostCompanyTotalAttribute()
	{
		if ($value = $this->cost_company_total)
			return round($value, 2);

		if ($this->getSellable()->isVehicleType())
			return round($this->getQuantity() * $this->calculated_cost_company * ($this->isRoundTrip() + 1), 2);

			if ($this->getSellable()->isRentType())
			if (! $this->cost_company_approver)
				return 0;

		return round($this->getQuantity() * $this->calculated_cost_company, 2);
	}

	public function getQuantity() : ?float
	{
		return $this->quantity;
	}

	public function setCalculatedCostCompanyAttribute($value)
	{
		$this->cost_company = $value;
	}

	public function getCalculatedTollHtmlClass() : string
	{
		if ($value = $this->toll)
			return 'tollforced';

		return 'tollstandard';
	}

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

	public function getCalculatedCostCompanyAttribute()
	{
		if ($value = $this->cost_company)
			return $value;

		if ($this->getSellable()->isContracttype())
		{
			if($valid = $this->getSupplier()?->getTarget()?->getValidClientOperator())
				return $valid->getPriceByCollectionId('costCompanyDay')->price;

			return $this->getSupplier()?->getTarget()->clientOperators->first()?->getPriceByCollectionId('costCompanyDay')->price;
		}

		if ($sellableSupplier = $this->getSellableSupplier())
			if ($price = $sellableSupplier->getPriceByCollectionId('costCompanyDay'))
				if ($value = $price->price)
					return $value;

		return $this->getSellable()->getCostCompany();
	}

	public function getDestination() : ?Destination
	{
		return $this->getModelContainer()?->getDestination();
	}

}