<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

use IlBronza\CRUD\Traits\CRUDIndexTrait;
use IlBronza\CRUD\Traits\CRUDPlainIndexTrait;


class ResetQuotationRowsIndexesController extends QuotationCRUD
{
	public $allowedMethods = ['resetRowsIndexes'];

	public function resetRowsIndexes(string $quotation)
	{
		$quotation = $this->getModelClass()::find($quotation);

		foreach(config('products.models.order.rowTypes') as $rowType)
		{
			$quotationRows = $quotation->$rowType()->orderBy('sorting_index')->get();

			$i = 1;

			foreach($quotationRows as $quotationRow)
			{
				$quotationRow->sorting_index = $i ++;
				$quotationRow->save();
			}
		}

		return back();
	}
}
