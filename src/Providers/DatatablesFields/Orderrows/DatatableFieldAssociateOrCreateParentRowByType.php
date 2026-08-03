<?php

namespace IlBronza\Products\Providers\DatatablesFields\Orderrows;

use IlBronza\Datatables\DatatablesFields\Form\DatatableFieldSubmit;

class DatatableFieldAssociateOrCreateParentRowByType extends DatatableFieldSubmit
{
	public $function = 'getAssociateOrCreateParentOrderrowByTypeUrl';
	public ?string $translationPrefix = 'products::datatableFields';
	public $faIcon = 'link';
}
