<?php

namespace IlBronza\Products\Providers\Helpers;

use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;

class OrderIntegrityCheckerHelper
{
	static function checkOrderIntegrity(Order $order)
	{
        if(! count($orderProducts = $order->getOrderProducts()))
        {
            $order->delete();

            return false;
        }

        foreach($orderProducts as $orderProduct)
        {
            if(! count($orderProductPhases = $orderProduct->getOrderProductPhases()))
            {
                $orderProduct->delete();

                if(! count($orderProducts = $order->orderProducts()->get()))
                    $order->delete();

            	return false;
            }
        }

        return true;
	}

	static function checkOrderProductIntegrity(OrderProduct $orderProduct)
	{
        if(! $order = $orderProduct->getOrder())
        {
        	foreach($orderProduct->getOrderProductPhases() as $orderProductPhase)
        		$orderProductPhase->delete();

            $orderProduct->delete();

            return false;
        }

        if(! count($orderProductPhases = $orderProduct->getOrderProductPhases()))
        {
            $orderProduct->delete();
            $order->delete();

            return false;
        }

        return true;
	}

	static function checkOrderProductPhaseIntegrity(OrderProductPhase $orderProductPhase)
	{
        if(! $orderProduct = $orderProductPhase->getOrderProduct())
        {
        	$orderProductPhase->delete();

            return false;
        }

        return true;
	}
}