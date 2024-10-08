<?php

namespace IlBronza\Products\Http\Controllers\Quotation;

class QuotationCurrentController extends QuotationIndexController
{
    public function getIndexElements()
    {
		ini_set('max_execution_time', 300);
		ini_set('memory_limit', - 1);

		return $this->getModelClass()::with(
            'project',
            'destination',
            'parent',
            'client',
            'category',
            'quotationrows.sellable',
            'quotationrows.price',
        )->current()->get();
    }

}
