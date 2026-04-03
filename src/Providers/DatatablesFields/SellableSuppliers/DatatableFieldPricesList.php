<?php

namespace IlBronza\Products\Providers\DatatablesFields\SellableSuppliers;

use IlBronza\Datatables\DatatablesFields\DatatableFieldFlat;

class DatatableFieldPricesList extends DatatableFieldFlat
{
	public ?string $translationPrefix = 'products::datatableFields';
	public ? string $forcedStandardName = 'pricesList';

	public function transformValue($value)
	{
		$result = [];

		foreach($value->getCachedPriceFieldsForSellable() as $priceField => $measurementUnit)
			$result[] = "{$priceField}: {$value->$priceField}";

		return implode("<br />", $result);
	}
}
