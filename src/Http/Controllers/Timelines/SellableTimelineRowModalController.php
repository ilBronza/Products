<?php

namespace IlBronza\Products\Http\Controllers\Timelines;

use IlBronza\Operators\Models\Sellables\OperatorOrderrow;

class SellableTimelineRowModalController extends BaseTimelineRowModalController
{
	public function getRowType() : string
	{
		return OperatorOrderrow::gpc();
	}
}
