<?php

namespace IlBronza\Products\Providers\DatatablesFields\Quotationrows;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldAssignSellableSupplier extends DatatableFieldLink
{
	public $function = 'getAssignSellableSupplierToQuotationrowUrl';
	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'plus';
}
