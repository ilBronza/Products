<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\FormField\FormField;
use IlBronza\Products\Http\Controllers\Supplier\AssociateOrCreateParentRowByTypeIndexByTableController;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use Illuminate\Http\Request;

class OrderAssociateOrCreateParentRowByTypeIndexByTableController extends AssociateOrCreateParentRowByTypeIndexByTableController
{
	protected function getRowcontainerModelClass() : string
	{
		return Order::class;
	}

	protected function getRowModelClass() : string
	{
		return Orderrow::class;
	}

	protected function getRowContainer($row) : ? Order
	{
		return $row->getOrder();
	}

	public function store(Request $request, $order, string $type, $supplier)
	{
		$this->type = $type;

		return $this->associateOrCreateParentRowByType(
			$request,
			Order::gpc()::findOrFail($order),
			$supplier
		);
	}
}
