<?php

namespace IlBronza\Products\Providers\Helpers\OrderProductPhases;

class OrderProductPhaseCheckCompletionHelper extends OrderProductPhaseBaseCompletionHelper
{
	static $classConfigPrefix = 'checkCompletionHelper';

	public function closeWithFailure(? string $message)
	{
		if($message)
			$this->addMessage($message);

		OrderProductPhaseUncompletionHelper::gpc()::execute($this->getOrderProductPhase());

		if($message = OrderProductPhaseUncompletionHelper::gpc()::getMessagesBag($this->getOrderProductPhase()))
			$this->addMessage($message);

		return false;
	}

	public function _execute() : bool
	{
		$orderProductPhase =  $this->getOrderProductPhase();

		if($orderProductPhase->isLast())
		{
			if(! $orderProductPhase->productionUnitloads()->count())
				return $this->closeWithFailure(
					trans('products::messages.noUnitloadsFound', ['model' => $orderProductPhase->getName()])
				);
			if($orderProductPhase->productionUnitloads()->unCompleted()->first())
				return $this->closeWithFailure(
					trans('products::messages.unitloadNotCompletedFound', ['model' => $orderProductPhase->getName()])
				);
		}


		if(! $orderProductPhase->processings()->final()->first())
			return $this->closeWithFailure(
				trans('products::messages.noFinalProcessingExisting', ['model' => $orderProductPhase->getName()])
			);

		if (! $orderProductPhase->processings()->forCompletion()->definitive()->byLast()->first())
			return $this->closeWithFailure();

		if(OrderProductPhaseCompletionHelper::gpc()::execute($orderProductPhase))
			return true;

		$this->addMessage(OrderProductPhaseCompletionHelper::gpc()::getMessagesBag($orderProductPhase));

		return false;
	}
}