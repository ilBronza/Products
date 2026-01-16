<?php

namespace IlBronza\Products\Providers\Helpers\OrderProductPhases;

use IlBronza\Products\Providers\Helpers\OrderProducts\OrderProductCompletionHelper;


class OrderProductPhaseCompletionHelper extends OrderProductPhaseBaseCompletionHelper
{
	static $classConfigPrefix = 'completionHelper';

	public function _execute()
	{
		OrderProductPhaseQuantityHelper::gpc()::calculate(
			$this->getOrderProductPhase()
		);

		if (! $lastCompletionProcessing = $this->getOrderProductPhase()->processings()->forCompletion()->definitive()->byLast()->first())
			throw new Exception('non trovato il processo che termina la lavorazione ' . $orderProductPhase->getName() . ' <a href="' . $orderProductPhase->getShowUrl() . '">Controlla qui</a>');

		$this->getOrderProductPhase()->_complete($lastCompletionProcessing);

		OrderProductCompletionHelper::gpc()::checkCompletion(
			$this->getOrderProductPhase()->getOrderProduct()
		);

		return true;
	}
}