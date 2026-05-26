<?php

namespace IlBronza\Products\Providers\DatatablesFields\Orderrows;

use IlBronza\Datatables\DatatablesFields\Form\DatatableFieldSubmit;

class DatatableFieldAddBySupplier extends DatatableFieldSubmit
{
	// public $function = 'getAssociateSupplierToSellableByOrderrowUrl';
	public $function = 'getAddOrderrowBySupplierUrl';

	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'save';
}
