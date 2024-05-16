<?php

namespace IlBronza\Products\Http\Controllers\Crons;

use App\Http\Controllers\Controller;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Providers\Helpers\OrderIntegrityCheckerHelper;

class OrdersIntegrityChecker extends Controller
{
    public function __construct()
    {
        parent::__construct();

        ini_set('max_execution_time', "120");
        ini_set('memory_limit', "-1");        
    }

    public function orders()
    {
        $orders = Order::getProjectClassName()::with('orderProducts.orderProductPhases')
            ->inRandomOrder()
            ->take(10000)
            ->get();

        foreach($orders as $order)
            OrderIntegrityCheckerHelper::checkOrderIntegrity($order);

        return [
            'success' => true
        ];
    }

    public function orderProducts()
    {
        $orderProducts = OrderProduct::getProjectClassName()::with('orderProductPhases', 'order')
            ->inRandomOrder()
            ->take(10000)
            ->get();

        foreach($orderProducts as $orderProduct)
            OrderIntegrityCheckerHelper::checkOrderProductIntegrity($orderProduct);

        return [
            'success' => true
        ];
    }

    public function orderProductPhases()
    {
        $orderProductPhases = OrderProductPhase::getProjectClassName()::with('orderProduct')
            ->inRandomOrder()
            ->take(10000)
            ->get();

        foreach($orderProductPhases as $orderProductPhase)
            OrderIntegrityCheckerHelper::checkOrderProductPhaseIntegrity($orderProductPhase);

        return [
            'success' => true
        ];
    }
}