<?php

namespace IlBronza\Products\Providers\DatatablesFields\Quotationrows;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldAssociateSupplier extends DatatableFieldLink
{
	public $function = 'getAssociateSupplierToSellableByQuotationrowUrl';

	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'plus';
}
