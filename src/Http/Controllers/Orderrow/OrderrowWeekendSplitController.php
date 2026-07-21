<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Providers\Helpers\Orderrows\OrderrowWeekendSplitterHelper;

class OrderrowWeekendSplitController extends OrderrowCRUD
{
	public $allowedMethods = ['store'];

	public function store(string $orderrow)
	{
		$orderrow = $this->findModel($orderrow);

		OrderrowWeekendSplitterHelper::split($orderrow);

		if (request()->query('closeIframe')) {
			return redirect()->route('iframe.close')->with('crud.success', __('products::orderrows.splitWeekendsSuccess'));
		}

		return redirect()->back()->with('crud.success', __('products::orderrows.splitWeekendsSuccess'));
	}
}
