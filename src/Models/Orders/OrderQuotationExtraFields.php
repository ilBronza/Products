<?php

namespace IlBronza\Products\Models\Orders;

use IlBronza\CRUD\Models\BaseModel;
use IlBronza\CRUD\Traits\Model\CRUDUseUuidTrait;

class OrderQuotationExtraFields extends BaseModel
{
	use CRUDUseUuidTrait;

	protected $keyType = 'string';

	public function getTable()
	{
		return 'products__extrafields__quotations_orders';
	}
}
