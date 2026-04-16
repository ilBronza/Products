<?php

namespace IlBronza\Products\Http\Controllers\Order;

use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsSellableSupplierAssociatorHelper;
use Illuminate\Http\Request;

class OrderStoreOrderrowsBySellableSupplierController extends OrderAddOrderrowIndexController
{
	public $allowedMethods = ['storeOrderrowsBySellableSupplier'];

	public function storeOrderrowsBySellableSupplier(Request $request, $order, string $type)
	{
		$order = $this->findModel($order);

		$sellableSupplierModel = SellableSupplier::gpc()::make();

		$parameters = $request->validate([
			'ids' => 'required|array',
			'ids.*' => 'exists:' . $sellableSupplierModel->getTable() . ',' . $sellableSupplierModel->getKeyName(),
		]);

		$orderrowSortingIndex = $this->getSortingIndexByType($order, $type);

		foreach ($parameters['ids'] as $sellableSupplierId)
		{
			$sellableSupplier = SellableSupplier::gpc()::query()
				->whereHas('sellable', fn ($q) => $q->byType($type))
				->with('sellable')
				->find($sellableSupplierId);

			if (! $sellableSupplier)
				abort(422);

			$sellable = $sellableSupplier->getSellable();

			$orderrow = Orderrow::gpc()::make();
			$orderrow->sellable()->associate($sellable);
			$orderrow->order()->associate($order);
			$orderrow->type = $sellable->type;
			$orderrow->sorting_index = $orderrowSortingIndex ++;
			$orderrow->save();

			RowsSellableSupplierAssociatorHelper::associateSellableSupplierToRow($orderrow, $sellableSupplier);
		}

		if ($url = session()->get('orderAddOrderrowIndexController_storeOrderrow_return_url'))
		{
			session()->forget('orderAddOrderrowIndexController_storeOrderrow_return_url');

			return redirect()->to($url);
		}

		return view('datatables::utilities.closeIframe', ['reloadAllTables' => true]);
	}
}
