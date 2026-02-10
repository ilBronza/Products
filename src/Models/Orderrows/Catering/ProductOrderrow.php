<?php

namespace IlBronza\Products\Models\Orderrows\Catering;

use IlBronza\Products\Models\Orderrows\ProductOrderrow as IbProductOrderrow;

class ProductOrderrow extends IbProductOrderrow
{
	public function getServedAtTableAttribute($value)
	{
		if(is_null($value))
			return $this->getSellable()?->getTarget()?->getServedAtTable();

		return $value;
	}
}