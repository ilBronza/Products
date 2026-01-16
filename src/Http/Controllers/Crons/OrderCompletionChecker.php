<?php

namespace IlBronza\Products\Http\Controllers\Crons;

use App\Http\Controllers\Controller;
use IlBronza\Products\Models\OrderProductPhase;

class OrderCompletionChecker extends Controller
{
    public function execute()
    {
        $orderProductPhases = OrderProductPhase::getProjectClassName()::with('orderProduct.orderProductPhases')->notCompleted()->inRandomOrder()->take(3000)
            ->get();

        foreach($orderProductPhases as $orderProductPhase)
        {
            if(! $orderProduct = $orderProductPhase->getOrderProduct())
            {
                $orderProductPhase->delete();

                continue;
            }

            if($orderProduct->isCompleted())
            {
                foreach($orderProductPhase->getOrderProductPhases() as $checkingOrderProductPhase)
                {
                    if($checkingOrderProductPhase->isCompleted())
                        continue;

	                OrderProductPhaseCheckCompletionHelper::gpc()::execute($checkingOrderProductPhase);

                    if($checkingOrderProductPhase->isCompleted())
                        continue;

                    $checkingOrderProductPhase->__complete($orderProduct->getCompletedAt());
                }
            }
        }

        return [
            'success' => true
        ];
    }
}