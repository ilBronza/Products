<?php

namespace IlBronza\Products\Models\Traits\Orderrow;

use IlBronza\Products\Models\Interfaces\CustomRowInterface;
use IlBronza\Products\Models\Order;

trait CommonOrderrowQuotationrowGettersTrait
{
	public function getDaysQuantityAttribute()
	{
		if (! $starts = $this->getStartsAt())
			return null;

		if (! $ends = $this->getEndsAt())
			return null;

		return $starts->diffInDays($ends) + 1;
	}

	public function getDaysQuantity() : ? float
	{
		return $this->days_quantity;
	}

	public function getType() : string
	{
		if(! $this->type)
			dd($this->getSellable());

		return $this->type;
	}

	public function getRowFieldsToStore() : array
	{
		if(! $target = $this->getSellable()->getTarget())
			return cconfig("app.fieldsToStoreByType.{$this->type}");

		return $target->getRowFieldsToStore();
	}

	public function getSpecificRow() : CustomRowInterface
	{
        $classMethod = "rowRelationBy{$this->getType()}";

        $modelContainer = $this->getModelContainer();

        return $modelContainer->{$classMethod}()->find($this->getKey());
	}
}