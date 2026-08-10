<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;

class OrderrowGenericChildrenController extends OrderrowCRUD
{
	public $allowedMethods = ['genericChildren'];

	public function genericChildren($orderrow)
	{
		$this->model = $this->findModel($orderrow);

		$children = RowsFinderHelper::getCompositeRowCollectionByIds(
			$this->model->genericChildren()->pluck('id'),
			['sellable', 'sellableSupplier.supplier.target', 'extraFields']
		);

		return view('products::rows.genericChildren', [
			'row' => $this->model,
			'children' => $children
		]);
	}
}
