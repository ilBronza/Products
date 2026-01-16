<?php

namespace IlBronza\Products\Providers\Helpers\OrderProductPhases;

use IlBronza\CRUD\Traits\Helpers\HelperMessageBagTrait;
use IlBronza\CRUD\Traits\PackagedHelpersTrait;
use IlBronza\Products\Models\OrderProductPhase;

class OrderProductPhaseBaseCompletionHelper
{
	use PackagedHelpersTrait;
	use HelperMessageBagTrait;

	static $packageConfigPrefix = 'products';
	static $modelConfigPrefix = 'orderProductPhase';

	public OrderProductPhase $orderProductPhase;

	static function execute(OrderProductPhase $orderProductPhase) : bool
	{
		$result = new static($orderProductPhase);

		return $result->_execute();
	}

	public function __construct(OrderProductPhase $orderProductPhase)
	{
		$this->orderProductPhase = $orderProductPhase;
	}

	public function getOrderProductPhase() : OrderProductPhase
	{
		return $this->orderProductPhase;
	}

	public function getSubjectModel() : OrderProductPhase
	{
		return $this->orderProductPhase;
	}

}