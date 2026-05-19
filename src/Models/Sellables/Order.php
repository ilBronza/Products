<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\Products\Models\Order as BaseOrder;
use IlBronza\Products\Models\Sellables\CommonSellableOrderQuotationTrait;

class Order extends BaseOrder
{
	use CommonSellableOrderQuotationTrait;
}