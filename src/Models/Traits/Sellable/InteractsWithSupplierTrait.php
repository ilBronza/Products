<?php

namespace IlBronza\Products\Models\Traits\Sellable;

use App\Models\ProjectSpecific\Supplier;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;

trait InteractsWithSupplierTrait
{
	public function supplier() : MorphOne
	{
        return $this->morphOne(Supplier::getProjectClassname(), 'target');
	}

	public function getSupplier() : ? Model
	{
		return $this->supplier;
	}

    public function scopeWithSupplierId($query)
    {
        $query->addSelect([
            'live_supplier_id' => Supplier::getProjectClassName()::select(
            	'id')
                    ->whereColumn('target_id', $this->getTable() . '.id')
                    ->where('target_type', $this->getMorphClass())
                    ->take(1)
        ]);
    }

	public function sellableSuppliers()
	{
		return $this->hasMany(
			SellableSupplier::getProjectClassName(),
			'supplier_id',
			'live_supplier_id'
		);
	}

	public function scopeWithSellableSuppliers($query)
	{
		$query->withSupplierId();
		$query->with('sellableSuppliers');
	}

	public function getSellableSuppliersBySupplier()
	{
		return $this->getSupplier()->getSellableSuppliers();
	}

	public function sellables()
	{
		return $this->belongsToMany(
			Sellable::getProjectClassName(),
			config('products.models.sellableSupplier.table'),
			'supplier_id',
			null,
			'live_supplier_id'
		)->using(SellableSupplier::getProjectClassName());
	}

	public function scopeWithSellables($query)
	{
		$query->withSupplierId();
		$query->with('sellables');
	}

	public function getSellablesBySupplier() : Collection
	{
		return $this->getSupplier()->getSellables();
	}

	public function getQuotationsBySupplier() : Collection
	{
		return $this->getSupplier()->getQuotations();
	}
}