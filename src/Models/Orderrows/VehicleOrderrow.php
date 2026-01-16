<?php

namespace IlBronza\Products\Models\Orderrows;

use IlBronza\Products\Models\Orders\CustomOrderrow;

class VehicleOrderrow extends CustomOrderrow
{
	protected static ?string $typeName = 'vehicle';
	public $routeBasename = 'ibProductsorderrows';
	public $routeClassname = 'orderrow';
}