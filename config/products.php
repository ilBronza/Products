<?php

use IlBronza\Products\Http\Controllers\Order\ProductOrderIndexController;
use IlBronza\Products\Http\Controllers\Phase\ProductPhaseIndexController;
use IlBronza\Products\Http\Controllers\Product\ProductCurrentController;
use IlBronza\Products\Http\Controllers\Product\ProductIndexController;
use IlBronza\Products\Http\Controllers\Product\ProductShowController;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ProductFieldsGroupParametersFile;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\Product;
use IlBronza\Products\Models\ProductRelation;

return [
    'routePrefix' => 'ibProducts',

    'models' => [
        'product' => [
            'class' => Product::class,
            'table' => 'products__products',
            'fieldsGroupParameters' => [
                'ProductIndexController' => ProductFieldsGroupParametersFile::class,
                'ProductCurrentController' => ProductFieldsGroupParametersFile::class,
            ],
            'controllers' => [
                'show' => ProductShowController::class,
                'index' => ProductIndexController::class,
                'current' => ProductCurrentController::class
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