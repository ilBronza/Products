<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Providers\Helpers\Orderrows\OrderrowSplitterHelper;

class OrderrowSplitController extends OrderrowCRUD
{
	public $allowedMethods = ['store'];

	public function store(string $orderrow)
	{
		$orderrow = $this->findModel($orderrow);

		OrderrowSplitterHelper::split($orderrow);

		if (request()->query('closeIframe')) {
			return redirect()->route('iframe.close')->with('crud.success', __('products::orderrows.splitSuccess'));
		}

		return redirect()->back()->with('crud.success', __('products::orderrows.splitSuccess'));
	}
}
