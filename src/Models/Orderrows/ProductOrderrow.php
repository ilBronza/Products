<?php

namespace IlBronza\Products\Models\Orderrows;

use IlBronza\Products\Models\Orders\CustomOrderrow;

use function dd;

class ProductOrderrow extends CustomOrderrow
{
	protected static ?string $typeName = 'product';

	public $routeBasename = 'ibProductsorderrows';
	public $routeClassname = 'orderrow';

	public function getCalculatedCostCompanyTotalHtmlClass()
	{
		dd('mettere questa su padovanio e basta');

		if ($value = $this->cost_company_total)
			return 'costcompanytotalforced';

		return 'costcompanytotalcalculated';
	}
}