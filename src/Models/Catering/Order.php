<?php

namespace IlBronza\Products\Models\Catering;

use IlBronza\Products\Models\Catering\CateringOrderQuotationTrait;
use IlBronza\Products\Models\Orders\OrderExtraFields;
use IlBronza\Products\Models\Sellables\Order as SellableOrder;

class Order extends SellableOrder
{
	use CateringOrderQuotationTrait;

	public function getExtraFieldsClass() : ? string
	{
		return OrderExtraFields::class;
	}

}