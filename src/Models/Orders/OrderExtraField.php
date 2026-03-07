<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\CRUD\Traits\Model\CRUDTimeRangesTrait;
use IlBronza\Products\Models\Order;

class OrderExtraFields extends OrderQuotationExtraFields
{
	use CRUDTimeRangesTrait;

	public function order()
	{
		return $this->belongsTo(Order::gpc());
	}
}
