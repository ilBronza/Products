<?php

namespace IlBronza\Products\Providers\DatatablesFields\Quotationrows;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldAssignBulkSellableSupplier extends DatatableFieldLink
{
	public $function = 'getAssignBulkSellableSupplierToQuotationrowUrl';
	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'circle-plus';
}
