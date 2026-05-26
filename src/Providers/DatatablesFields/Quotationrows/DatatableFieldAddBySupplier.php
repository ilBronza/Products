<?php

namespace IlBronza\Products\Providers\DatatablesFields\Quotationrows;

use IlBronza\Datatables\DatatablesFields\Form\DatatableFieldSubmit;

class DatatableFieldAddBySupplier extends DatatableFieldSubmit
{
	// public $function = 'getAssociateSupplierToSellableByOrderrowUrl';
	public $function = 'getAddQuotationrowBySupplierUrl';

	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'save';
}
