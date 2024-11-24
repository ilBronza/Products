<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Products\Http\Controllers\CRUDProductPackageController;

class OrderCRUD extends CRUDProductPackageController
{
	static $modelConfigPrefix = 'order';

	public $configModelClassName = 'order';
}
