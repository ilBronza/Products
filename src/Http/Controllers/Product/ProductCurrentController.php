<?php

namespace IlBronza\Products\Http\Controllers\Product;

class ProductCurrentController extends ProductIndexController
{
    public function getIndexElements()
    {
        ini_set('max_execution_time', "120");
        ini_set('memory_limit', "2048M");

        return $this->getModelClass()::current()
            ->with([
                'client' => function($query)
                {
                    $query->select('id', 'name');
                },
                'orders'
            ])->get();
    }
}
