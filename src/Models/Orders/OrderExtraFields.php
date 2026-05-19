<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\CRUD\Traits\Model\CRUDTimeRangesTrait;
use IlBronza\Products\Models\Order;

class OrderExtraFields extends OrderQuotationExtraFields
{
	use CRUDTimeRangesTrait;

	protected static function booted()
	{
		static::updating(function (self $model)
		{
			if(
				$model->isDirty('activate_men_handling_costs')
				|| $model->isDirty('activate_general_expenses_costs')
			)
				$model->saved_total_costs = 0;
		});
	}

	public function order()
	{
		return $this->belongsTo(Order::gpc());
	}
}
