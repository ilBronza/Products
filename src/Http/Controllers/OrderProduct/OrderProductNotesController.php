<?php

namespace IlBronza\Products\Http\Controllers\OrderProduct;

use App\Models\ProductsPackage\OrderProduct;
use IlBronza\Notes\Http\Controllers\AbstractPopupNoteController;

class OrderProductNotesController extends AbstractPopupNoteController
{
	public function getPopupByModel(OrderProduct $orderProduct)
	{
		return $this->_getPopupByModel(
			$orderProduct
		);
	}
}
