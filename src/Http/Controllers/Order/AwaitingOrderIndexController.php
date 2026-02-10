<?php

namespace IlBronza\Products\Http\Controllers\Order;

class AwaitingOrderIndexController extends ActiveOrderIndexController
{
    public function getIndexElements()
    {
        return $this->getModelClass()::awaiting()->with([
            'client' => function($query)
            {
                $query->select('id', 'name');
            }
        ])->get();
    }

}
