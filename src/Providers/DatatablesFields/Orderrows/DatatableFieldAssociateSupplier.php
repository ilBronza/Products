<?php

namespace IlBronza\Products\Providers\DatatablesFields\Orderrows;

use IlBronza\Datatables\DatatablesFields\Links\DatatableFieldLink;

class DatatableFieldAssociateSupplier extends DatatableFieldLink
{
//	public $function = 'getAssignSellableSupplierToOrderrowUrl';

	public $function = 'getAssociateSupplierToSellableByOrderrowUrl';

	public ?string $translationPrefix = 'products::datatableFields';

	public $faIcon = 'plus';
}
