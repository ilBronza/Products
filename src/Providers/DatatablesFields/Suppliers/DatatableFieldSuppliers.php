<?php

namespace IlBronza\Products\Providers\DatatablesFields\Suppliers;

use IlBronza\Datatables\DatatablesFields\DatatableFieldFlat;

class DatatableFieldSuppliers extends DatatableFieldFlat
{
	public function getTranslatedName()
	{
		return trans('products::fields.suppliersList');
	}

	public function transformValue($value)
	{
		return $value->getSuppliersList()->pluck('name')->join('<br />');
	}
}