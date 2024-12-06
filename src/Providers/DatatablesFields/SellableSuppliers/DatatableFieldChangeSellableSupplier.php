<?php

namespace IlBronza\Products\Providers\DatatablesFields\SellableSuppliers;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldChangeSellableSupplier extends DatatableFieldLink
{
	public bool|string $lightbox = true;

	public $defaultWidth = '25px';
	public $faIcon = 'shuffle';
	public ? string $translationPrefix = 'products::datatableFields';

	public function transformValue($value)
	{
		if(! $value)
			return null;

		return $value->getAssignSellablesupplierUrl();
	}
}
