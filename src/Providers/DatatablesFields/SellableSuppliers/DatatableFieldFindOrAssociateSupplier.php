<?php

namespace IlBronza\Products\Providers\DatatablesFields\SellableSuppliers;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldFindOrAssociateSupplier extends DatatableFieldLink
{
	public bool|string $lightbox = true;

	public $defaultWidth = '25px';
	public $faIcon = 'plus';
	public ? string $translationPrefix = 'products::datatableFields';

	public function getTranslatedName()
	{
		return trans('products::fields.findOrAssociateSupplier');
	}

	public function transformValue($value)
	{
		if(! $value)
			return null;

		return $value->getFindOrAssociateSupplierUrl();
	}
}
