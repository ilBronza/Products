<?php

namespace IlBronza\Products\Providers\DatatablesFields\Quotationrows;

use IlBronza\Datatables\DatatablesFields\Form\DatatableFieldSubmit;

class DatatableFieldAssociateOrCreateParentRowByType extends DatatableFieldSubmit
{
	public $function = 'getAssociateOrCreateParentQuotationrowByTypeUrl';
	public ?string $translationPrefix = 'products::datatableFields';
	public $faIcon = 'link';
}
