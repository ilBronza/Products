<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Products\Http\Controllers\CRUDProductPackageShowTrait;
use IlBronza\Products\Http\Controllers\Order\OrderCRUD;

class OrderShowController extends OrderCRUD
{
    use CRUDProductPackageShowTrait;
    public $allowedMethods = ['show'];

    public function show($order)
    {
        $order = $this->findModel($order);

	    if(! $order->isFrozen())
		    $this->addNavbarButton(
			    $order->getFreezeButton()
		    );

	    return $this->_show($order);
    }
}
