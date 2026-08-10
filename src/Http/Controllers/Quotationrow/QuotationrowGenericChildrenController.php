<?php

namespace IlBronza\Products\Http\Controllers\Quotationrow;

use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;

class QuotationrowGenericChildrenController extends QuotationrowCRUD
{
	public $allowedMethods = ['genericChildren'];

	public function genericChildren($quotationrow)
	{
		$this->model = $this->findModel($quotationrow);

		$children = RowsFinderHelper::getQuotationCompositeRowCollectionByIds(
			$this->model->genericChildren()->pluck('id'),
			['sellable', 'sellableSupplier.supplier.target', 'extraFields']
		);

		return view('products::rows.genericChildren', [
			'row' => $this->model,
			'children' => $children
		]);
	}
}
