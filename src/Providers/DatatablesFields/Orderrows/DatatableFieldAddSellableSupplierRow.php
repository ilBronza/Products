<?php

namespace IlBronza\Products\Providers\DatatablesFields\Orderrows;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldAddSellableSupplierRow extends DatatableFieldLink
{
	// public $function = 'getAssignSellableSupplierToOrderrowUrl';

	public $function = 'getAddSellableSupplierRowToOrderUrl';

	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'plus';
}
