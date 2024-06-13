<?php

namespace IlBronza\Products\Models\Traits\Sellable;

use App\Models\ProjectSpecific\Supplier;
use IlBronza\Products\Models\Sellables\Sellable;
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
}