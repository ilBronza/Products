<?php

namespace IlBronza\Products\Models\Sellables;

use IlBronza\Products\Models\Quotations\CustomQuotationrow;
use IlBronza\Products\Models\Sellables\ProductRowQuotationOrderCommonTrait;

class ProductQuotationrow extends CustomQuotationrow
{
	public string $fieldsGroupParametersKey = 'productQuotationrow';
	protected static ?string $typeName = 'Product';
	static $designedTargetConfigPackagePrefix = 'products';	

	use ProductRowQuotationOrderCommonTrait;
}