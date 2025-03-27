<?php

namespace IlBronza\Products\Models\Traits\Order;

use App\Models\ProjectSpecific\Destination;
use IlBronza\Category\Models\Category;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use Illuminate\Support\Collection;

trait CommonOrderrowQuotationrowRelationAndScopesTrait
{
	public function sellableSupplier()
	{
		return $this->belongsTo(SellableSupplier::gpc());
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

	public function scopeBySellableTypes($query, array $sellableTypes)
	{
		return $query->whereHas('sellable', function ($query) use ($sellableTypes)
		{
			$query->whereIn('type', $sellableTypes);
		});
	}

	public function scopeBySellableCategory($query, string|Category $category)
	{
		if (is_string($category))
			$category = Category::gpc()::findCachedField('name', $category);

		return $query->whereHas('sellable', function ($query) use ($category)
		{
			$query->where('category_id', $category->id);
		});
	}
	
	public function getSellable() : ?Sellable
	{
		if ($this->relationLoaded('sellable'))
			return $this->sellable;

		return $this->sellable()->first();
	}

	public function sellable()
	{
		return $this->belongsTo(Sellable::gpc());
	}

	public function getDestination() : ?Destination
	{
		return $this->getModelContainer()?->getDestination();
	}

	public function getInvoiceableDetail() : string
	{
		if ($this->description)
			return $this->description;

		return $this->getSellable()->getName();
	}
}