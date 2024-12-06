<?php

namespace IlBronza\Products\Providers\DatatablesFields\SellableSuppliers;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldAssignSellableSupplierToQuotationrow extends DatatableFieldLink
{
	public $function = 'getAssignSellableSupplierUrl';
	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'plus';
}
