<?php

namespace IlBronza\Products\Providers\DatatablesFields\Quotationrows;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

use function json_encode;

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
