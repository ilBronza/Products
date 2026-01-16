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

		$orderProductPhase->setCompletedAt(null);
		$orderProductPhase->setStatus('waiting');
		$orderProductPhase->timing()->forceDelete();

		$orderProductPhase->save();

		OrderProductCompletionHelper::gpc()::checkCompletion($orderProductPhase->getOrderProduct());

		return true;
	}


//	static function checkCompletion(OrderProductPhase $orderProductPhase)
//	{
//		if(! $orderProductPhase->productionUnitloads()->count())
//			return static::uncomplete($orderProductPhase, [
//				trans('products::messages.noUnitloadsFound', ['model' => $orderProductPhase->getName()])
//			]);
//
//		if($orderProductPhase->productionUnitloads()->unCompleted()->first())
//			return static::uncomplete($orderProductPhase, [
//				trans('products::messages.unitloadNotCompletedFound', ['model' => $orderProductPhase->getName()])
//			]);
//
//		if(! $orderProductPhase->processings()->final()->first())
//			return static::uncomplete($orderProductPhase, [
//				trans('products::messages.noFinalProcessingExisting', ['model' => $orderProductPhase->getName()])
//			]);
//
//		if (! $orderProductPhase->processings()->forCompletion()->definitive()->byLast()->first())
//			return static::uncomplete($orderProductPhase);
//
//		return static::complete($orderProductPhase);
//	}
}