<?php

namespace IlBronza\Products\Models\Traits\Accessory;

use IlBronza\Products\Models\AccessoryProduct;
use IlBronza\Products\Models\AccessoryType;
use IlBronza\Products\Models\Product\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

trait AccessoryRelationshipsScopesTrait
{
	public function accessoryType() : BelongsTo
	{
		return $this->belongsTo(AccessoryType::gpc());
	}

	public function getAccessoryType() : ? AccessoryType
	{
		return $this->accessoryType;
	}

	public function accessoryProducts() : HasMany
	{
		return $this->hasMany(AccessoryProduct::gpc());
	}

	public function getAccessoryProducts() : Collection
	{
		return $this->accessoryProducts;
	}

	public function products() : BelongsToMany
	{
		return $this->belongsToMany(
			Product::gpc()
		)->using(
			AccessoryProduct::gpc()
		);
	}

	public function getProducts() : Collection
	{
		return $this->products;
	}
}

