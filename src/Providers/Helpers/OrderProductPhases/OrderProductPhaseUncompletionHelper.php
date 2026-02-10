<?php

namespace IlBronza\Products\Providers\Helpers\OrderProductPhases;

use IlBronza\Products\Providers\Helpers\OrderProducts\OrderProductCompletionHelper;

class OrderProductPhaseUncompletionHelper extends OrderProductPhaseBaseCompletionHelper
{
	static $classConfigPrefix = 'uncompletionHelper';

	public function _execute()
	{
		$orderProductPhase =  $this->getOrderProductPhase();

		OrderProductPhaseQuantityHelper::gpc()::calculate($orderProductPhase);

		if(count($orderProductPhase->getProcessings()) == 0)
			$orderProductPhase->setStartedAt(null);

		$orderProductPhase->setCompletedAt(null);
		$orderProductPhase->setStatus('waiting');
		$orderProductPhase->timing()->forceDelete();

		$orderProductPhase->save();

		OrderProductCompletionHelper::gpc()::checkCompletion($orderProductPhase->getOrderProduct());

		return true;
	}
}