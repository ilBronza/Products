<?php

namespace IlBronza\Products\Providers\DatatablesFields\OrderProducts;

use IlBronza\Datatables\DatatablesFields\DatatableFieldFlat;

class DatatableFieldPiecesDone extends DatatableFieldFlat
{
	public function transformValue($value)
	{
		return $value->getQuantityDone();
	}
}
