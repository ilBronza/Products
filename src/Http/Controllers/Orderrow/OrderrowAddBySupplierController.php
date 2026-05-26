<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Http\Controllers\Order\OrderCRUD;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use Illuminate\Http\Request;

class OrderrowAddBySupplierController extends OrderCRUD
{
	public $allowedMethods = [
		'addOrderrowBySupplier'
	];

	public function addOrderrowBySupplier(Request $request, $order, $type, $supplier)
	{
		$order = $this->findModel($order);

		$supplier = Supplier::gpc()::find($supplier);

		if($sellableId = $request->sellable_id)
		{
			if(! $sellable = Sellable::gpc()::find($sellableId))
				$sellable = Sellable::provideGenericByType($type);
		}
		else
			$sellable = Sellable::provideGenericByType($type);

		$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $sellable);

		$result = RowAssociatorHelper::associateRowBySellableSupplier($order, $sellableSupplier);

		$tablesToRefresh = $result->row->getTablesToRefresh();

		return view('datatables::utilities.closeIframe', [
			'tablesToRefresh' => $tablesToRefresh
		]);
	}
}
