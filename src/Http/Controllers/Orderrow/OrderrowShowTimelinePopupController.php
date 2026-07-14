<?php

namespace IlBronza\Products\Http\Controllers\Orderrow;

use IlBronza\Products\Http\Controllers\Orderrow\OrderrowCRUD;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsFinderHelper;

class OrderrowShowTimelinePopupController extends OrderrowCRUD
{
    public $allowedMethods = ['showTimelinePopup'];

	public function showTimelinePopup($orderrow)
	{
		$orderrow = RowsFinderHelper::getCustomSpecificRowById($orderrow);

		$packageName = $orderrow->getDesignedTargetConfigPackagePrefix();

		$classname = lcfirst(
			class_basename(
				$orderrow
			)
		);

		return view("{$packageName}::customrows.{$classname}", compact('orderrow'));
	}	
}
