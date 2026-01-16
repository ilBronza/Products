<?php

namespace IlBronza\Products\Providers\Helpers\OrderProducts;

use App\State;
use IlBronza\CRUD\Traits\PackagedHelpersTrait;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Providers\Helpers\Orders\OrderCompletionHelper;
use IlBronza\Timings\Helpers\TimingRemoverHelper;

class OrderProductCompletionHelper
{
	use PackagedHelpersTrait;

	static $packageConfigPrefix = 'products';
	static $modelConfigPrefix = 'orderProduct';
	static $classConfigPrefix = 'completionHelper';


	static function reset(OrderProduct $orderProduct)
	{
		foreach($orderProduct->getOrderProductPhases() as $orderProductPhase)
			$orderProductPhase->reset();

		$orderProduct->setStateId(null);
		$orderProduct->setQuantityDone(null);
		$orderProduct->setManualPiecesDone(null);
		$orderProduct->setManualPiecesDoneUpdatedBy(null);
		$orderProduct->setManualCardboardsToUnload(null);
		// $orderProduct->setCardboardsCountDifference(null);
		$orderProduct->setBrokenCount(null);
		$orderProduct->setPartiallyCompletedAt(null);
		$orderProduct->setStartedAt(null);
		$orderProduct->setCompletedAt(null);

		$orderProduct->save();

		OrderCompletionHelper::gpc()::checkCompletion($orderProduct->getOrder());

		return $orderProduct;
	}

	static function complete(OrderProduct $orderProduct)
	{
		if (! $lastOrderProductPhase = $orderProduct->getLastOrderProductPhase())
			throw new Exception('Ultima fase non trovata per componente ' . $orderProduct->getName() . ' <a href="' . $orderProduct->getEditUrl() . '">Controlla qui</a>');

		$orderProduct->setCompletedAt(
			$lastOrderProductPhase->getCompletedAt()
		);

		$orderProduct->setQuantityDone(
			$lastOrderProductPhase->getQuantityDone()
		);

		$orderProduct->setStateId(
			State::getTerminatedState()->id
		);

		$orderProduct->save();

		OrderCompletionHelper::gpc()::checkCompletion($orderProduct->getOrder());
	}

	static function uncomplete(OrderProduct $orderProduct)
	{
		$orderProduct->setQuantityDone($orderProduct->getLastOrderProductPhase()?->getQuantityDone());
		$orderProduct->setCompletedAt(null);
		$orderProduct->setStateId(null);
		$orderProduct->save();

		TimingRemoverHelper::remove($orderProduct);

		OrderCompletionHelper::gpc()::checkCompletion($orderProduct->getOrder());
	}

	static function checkCompletion(OrderProduct $orderProduct)
	{
		if(! count($orderProductPhases = $orderProduct->getOrderProductPhases()))
			return ;

		foreach($orderProductPhases as $orderProductPhase)
			if(! $orderProductPhase->isCompleted())
				return static::uncomplete($orderProduct);

		return static::complete($orderProduct);
	}
}