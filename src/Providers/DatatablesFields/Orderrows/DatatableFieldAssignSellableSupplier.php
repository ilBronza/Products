<?php

namespace IlBronza\Products\Providers\DatatablesFields\Orderrows;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldAssignSellableSupplier extends DatatableFieldLink
{
	public $function = 'getAssignSellableSupplierToOrderrowUrl';
	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'plus';
}
