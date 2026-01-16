<?php

namespace IlBronza\Products\Providers\Helpers\OrderProductPhases;

use IlBronza\CRUD\Traits\PackagedHelpersTrait;
use IlBronza\Products\Models\OrderProductPhase;

class OrderProductPhaseQuantityHelper
{
	use PackagedHelpersTrait;

    static $packageConfigPrefix = 'products';
	static $modelConfigPrefix = 'orderProductPhase';
	static $classConfigPrefix = 'quantityHelper';

	static function calculate(OrderProductPhase $orderProductPhase)
	{
		if($orderProductPhase->isLast())
			return $orderProductPhase->setQuantityDone(
				$orderProductPhase->productionUnitloads()->completed()->sum('quantity')
			);

		return $orderProductPhase->setQuantityDone(
			$orderProductPhase->processings->where('processing_type', 'production')->sum('valid_pieces_done')
		);
	}
}