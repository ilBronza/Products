<?php

namespace IlBronza\Products\Models\Catering;

use IlBronza\Products\Models\Product\Product as IbProduct;

class Product extends IbProduct
{
	public function getServedAtTable() : bool
	{
		return !! $this->served_at_table;
	}
}