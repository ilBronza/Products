<?php

namespace IlBronza\Products\Providers\Helpers;

use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;

class OrderProductSequenceCalculatorHelper
{
	static function calculateByOrderProduct(OrderProduct $orderProduct) : OrderProduct
	{
		$orderProductPhases = $orderProduct->orderProductPhases()->orderBy('sorting_index')->get();
		// $orderProductPhases = OrderProductPhase::whereNotNull('sorting_index')->take(19)->get();

		$groupedByValue = $orderProductPhases->groupBy('sorting_index');

    	$dupes = $groupedByValue->filter(function ( $groups) {
        	return $groups->count() > 1;
    	});

		// if(count($dupes))
		// 	throw new \Exception ("trovato una sequenza duplicata in questo componente OrderProductSequenceCalculatorHelper@calculateByOrderProduct");

		$sortingIndex = 0;

		foreach($orderProductPhases->sortBy('sorting_index') as $orderProductPhase)
			$orderProductPhase->setSequence($sortingIndex ++, true);

		return $orderProduct;
	}
}