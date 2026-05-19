<?php

namespace IlBronza\Products\Models\Orderrows\Catering;

trait CateringProductRowCommonTrait
{
	public function getServedAtTableAttribute($value)
	{
		if(is_null($value))
			return $this->getSellable()?->getTarget()?->getServedAtTable();

		return $value;
	}

	public function getQuantityCoefficient()
	{
		return $this->quantity_coefficient ?? 1;
	}

	public function getQuantityAttribute($value)
	{
		if ($value)
			return $value;

		if($this->people_coefficient)
			if($value = $this->getModelContainer()->getQuantityByPeopleCoefficient($this->people_coefficient))
				return $value;

		if (! $quantity = $this->getModelContainer()->base_quantity)
			return 0;

		return ceil($quantity * $this->getQuantityCoefficient());
	}

	public function getPdfDescription() : ? string
	{
		$description = $this->client_description;

		if($allergens = $this->getSellable()?->getTarget()?->getAllergensList())
			$description .= "<span class='in-row-allergene'>" . $allergens->implode('name', ' - ') . "</span>";

		return $description;
	}
}
