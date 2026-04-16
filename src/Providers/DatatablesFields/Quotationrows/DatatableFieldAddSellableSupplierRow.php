<?php

namespace IlBronza\Products\Providers\DatatablesFields\Quotationrows;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldAddSellableSupplierRow extends DatatableFieldLink
{
	// public $function = 'getAssignSellableSupplierToQuotationrowUrl';

	public $function = 'getAddSellableSupplierRowToQuotationUrl';

	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'plus';
}
