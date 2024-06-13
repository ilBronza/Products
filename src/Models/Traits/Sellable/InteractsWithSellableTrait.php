<?php

namespace IlBronza\Products\Models\Traits\Sellable;

use IlBronza\Products\Models\Sellables\Sellable;
use Illuminate\Support\Collection;

trait InteractsWithSellableTrait
{
	public function getNameForSellable(... $parameters) : string
	{
		return $this->getName();
	}

    static public function getPossibleSellableElements() : Collection
    {
        return static::all();
    }

	public function sellables()
	{
		return $this->morphMany(Sellable::getProjectClassname(), 'target');
	}

	public function getSellables() : Collection
	{
		return $this->sellables;
	}
}