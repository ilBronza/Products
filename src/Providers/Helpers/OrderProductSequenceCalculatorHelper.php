<?php

namespace IlBronza\Products\Providers\Helpers;

use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;

class OrderProductSequenceCalculatorHelper
{
	static function calculateByOrderProduct(OrderProduct $orderProduct) : OrderProduct
	{
		$orderProductPhases = $orderProduct->orderProductPhases()->orderBy('sequence')->get();
		// $orderProductPhases = OrderProductPhase::whereNotNull('sequence')->take(19)->get();

		$groupedByValue = $orderProductPhases->groupBy('sequence');

    	$dupes = $groupedByValue->filter(function ( $groups) {
        	return $groups->count() > 1;
    	});

    	if(count($dupes))
			throw new \Exception ("trovato una sequenza duplicata in questo componente OrderProductSequenceCalculatorHelper@calculateByOrderProduct");

		$sequence = 0;

		foreach($orderProductPhases as $orderProductPhase)
			$orderProductPhase->setSequence($sequence ++, true);

		return $orderProduct;
	}
}