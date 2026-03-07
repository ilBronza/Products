<?php

namespace IlBronza\Products\Models\Orderrows\Catering;

use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\Products\Models\Orderrows\ProductOrderrow as IbProductOrderrow;

class ProductOrderrow extends IbProductOrderrow
{
	protected $casts = [
		'deleted_at' => 'date',
		'client_price' => ExtraField::class,
		'company_cost' => ExtraField::class,
		'confirmed' => ExtraField::class,
		'forced_client_price' => ExtraField::class,
		'served_at_table' => ExtraField::class,
		'phase' => ExtraField::class,
		'people_coefficient' => ExtraField::class,
	];

	public function getServedAtTableAttribute($value)
	{
		if(is_null($value))
			return $this->getSellable()?->getTarget()?->getServedAtTable();

		return $value;
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
}