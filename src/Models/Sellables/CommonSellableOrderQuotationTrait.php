<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\CRUD\Models\Casts\ExtraField;
use IlBronza\Products\Models\Traits\Orderrow\ExclusiveDiscountFieldsTrait;

trait CommonSellableOrderQuotationTrait
{
	use ExclusiveDiscountFieldsTrait;

	public function initializeCommonSellableOrderQuotationTrait()
	{
		$this->mergeCasts([
			'discount_selection' => ExtraField::class,
			'discount_neat' => ExtraField::class,
			'discount_percentage' => ExtraField::class,
			'mup_selection' => ExtraField::class,
			'mup_revenue' => ExtraField::class,
			'mup_cost' => ExtraField::class,
			'cost_coefficient' => ExtraField::class,
			'revenue_coefficient' => ExtraField::class,
			'total_proposal' => ExtraField::class,
			'state_id' => ExtraField::class,
		]);
	}
}