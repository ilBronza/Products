<?php

namespace IlBronza\Products\Providers\DatatablesFields\Products;

use IlBronza\Datatables\DatatablesFields\DatatableField;

class DatatableFieldShortDescription extends DatatableField
{
	public function transformValue($value)
	{
		if(! $value)
			return ;

		return $value->short_description;
	}
}
