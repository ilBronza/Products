<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\Products\Models\Orders\CustomOrderrow;
use IlBronza\Products\Models\Sellables\AccessoryRowQuotationOrderCommonTrait;

class AccessoryOrderrow extends CustomOrderrow
{
	public string $fieldsGroupParametersKey = 'accessoryOrderrow';
	protected static ?string $typeName = 'AccessoryType';
	static $designedTargetConfigPackagePrefix = 'products';	

	use AccessoryRowQuotationOrderCommonTrait;
}