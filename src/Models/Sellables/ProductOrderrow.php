<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\Products\Models\Orders\CustomOrderrow;
use IlBronza\Products\Models\Sellables\ProductRowQuotationOrderCommonTrait;

class ProductOrderrow extends CustomOrderrow
{
	protected static ?string $typeName = 'Product';
	static $designedTargetConfigPackagePrefix = 'products';	

	use ProductRowQuotationOrderCommonTrait;
}