<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Products\Http\Traits\RowContainerRowCopyTrait;
use IlBronza\Products\Models\Order;

class OrderReplicateOrderrowController extends OrderCRUD
{
	use RowContainerRowCopyTrait;

	public function replicateLastRowByType($order, string $type)
	{
		if(! $order = Order::gpc()::find($order))
			return $this->closeIframe();

		return $this->_replicateLastRowByType($order, $type);
	}
}
