<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\CRUD\Interfaces\CrudReorderableModelInterface;
use IlBronza\Payments\Models\Interfaces\InvoiceDetailInterface;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\ProductPackageBaseRowModel;
use IlBronza\Products\Models\Traits\Order\CommonOrderrowQuotationrowTrait;
use IlBronza\Products\Models\Traits\Orderrow\OrderrowRelationsScopesTrait;

class Orderrow extends ProductPackageBaseRowModel implements CrudReorderableModelInterface, InvoiceDetailInterface
{
	static $modelConfigPrefix = 'orderrow';

	use CommonOrderrowQuotationrowTrait;
	use OrderrowRelationsScopesTrait;

	public $classnameAbbreviation = 'or';

	public function getModelContainer() : ?Order
	{
		return $this->getOrder();
	}

	public function getOrder() : ?Order
	{
		return $this->order;
	}

}