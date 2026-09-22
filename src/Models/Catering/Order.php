<?php

namespace IlBronza\Products\Models\Catering;

use IlBronza\Products\Models\Catering\CateringOrderQuotationTrait;
use IlBronza\Products\Models\Orders\OrderExtraFields;
use IlBronza\Products\Models\Sellables\Order as SellableOrder;

class Order extends SellableOrder
{
	use CateringOrderQuotationTrait;

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		if(! isset($this->attributes['people_coefficient']))
			$this->people_coefficient = $this->getDefaultPeopleCoefficient();
	}

	public function getDefaultPeopleCoefficient() : array
	{
		return [
			[
				'name' => config('products.catering.defaultPeopleCoefficientName'),
				'quantity' => null,
				'price_coefficient' => null,
				'calculated_price' => null,
				'price' => null
			]
		];
	}

	public function getBaseQuantityAttribute()
	{
		return array_sum(
			array_column($this->people_coefficient, 'quantity')
		);
	}

	public function setBaseQuantityAttribute()
	{
		throw new \Exception('Impossibile salvare il valore di quantità base. Si deve usare il pannello di gestione delle persone');
	}

	public function getExtraFieldsClass() : ? string
	{
		return OrderExtraFields::class;
	}

}