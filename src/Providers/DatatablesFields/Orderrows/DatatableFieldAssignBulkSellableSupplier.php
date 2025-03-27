<?php

namespace IlBronza\Products\Providers\DatatablesFields\Orderrows;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldAssignBulkSellableSupplier extends DatatableFieldLink
{
	public $function = 'getAssignBulkSellableSupplierToOrderrowUrl';
	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'circle-plus';
}
