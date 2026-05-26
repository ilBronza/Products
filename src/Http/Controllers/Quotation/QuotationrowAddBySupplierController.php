<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\Products\Http\Controllers\Quotation\QuotationCRUD;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use IlBronza\Products\Providers\Helpers\Sellables\SellableSupplierCreatorHelper;
use Illuminate\Http\Request;

class QuotationrowAddBySupplierController extends QuotationCRUD
{
	public $allowedMethods = [
		'addQuotationrowBySupplier'
	];

	public function addQuotationrowBySupplier(Request $request, $quotation, $type, $supplier)
	{
		$quotation = $this->findModel($quotation);

		$supplier = Supplier::gpc()::find($supplier);

		if($sellableId = $request->sellable_id)
		{
			if(! $sellable = Sellable::gpc()::find($sellableId))
				$sellable = Sellable::provideGenericByType($type);
		}
		else
			$sellable = Sellable::provideGenericByType($type);

		$sellableSupplier = SellableSupplierCreatorHelper::getOrCreateSellableSupplier($supplier, $sellable);

		$result = RowAssociatorHelper::associateRowBySellableSupplier($quotation, $sellableSupplier);

		$tablesToRefresh = $result->row->getTablesToRefresh();

		return view('datatables::utilities.closeIframe', [
			'tablesToRefresh' => $tablesToRefresh
		]);
	}
}
