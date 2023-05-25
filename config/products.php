<?php

use App\Http\Controllers\PackageOverriding\Products\ProductIndexController;
use App\Models\ProductsPackage\Order;
use App\Models\ProductsPackage\Product;
use App\Workstation;




use IlBronza\Products\Http\Controllers\Order\ProductOrderIndexController;
use IlBronza\Products\Http\Controllers\Phase\ProductPhaseIndexController;
use IlBronza\Products\Http\Controllers\Product\ProductShowController;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\ProductRelation;

return [
    'routePrefix' => 'ibProducts',

    'models' => [
        'product' => [
            'class' => Product::class,
            'table' => 'products__products',
            'controllers' => [
                'show' => ProductShowController::class,
                'index' => ProductIndexController::class
            ],
        ],
        'phase' => [
            'class' => Phase::class,
            'table' => 'products__phases',
            'controllers' => [
                'productPhaseIndex' => ProductPhaseIndexController::class
            ]
        ],
        'order' => [
            'class' => Order::class,
            'table' => 'products__orders',
            'controllers' => [
                'productOrderIndex' => ProductOrderIndexController::class
            ]
        ],
        'orderProduct' => [
            'class' => OrderProduct::class,
            'table' => 'products__order_products',
        ],
        'orderProductPhase' => [
            'class' => OrderProductPhase::class,
            'table' => 'products__order_product_phases',
        ],
        'productRelation' => [
            'class' => ProductRelation::class,
            'table' => 'products__product_relations',
        ],
        'workstation' => [
            'class' => Workstation::class,
            'table' => 'workstations',
        ]
    ]
];