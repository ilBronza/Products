<?php

use IlBronza\Products\Http\Controllers\Accessory\AccessoryCrudController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseShowController;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductEditUpdateController;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductShowController;
use IlBronza\Products\Http\Controllers\Order\OrderDeletionController;
use IlBronza\Products\Http\Controllers\Order\OrderEditUpdateController;
use IlBronza\Products\Http\Controllers\Order\OrderIndexController;
use IlBronza\Products\Http\Controllers\Order\OrderShowController;
use IlBronza\Products\Http\Controllers\Order\OrderTeaserController;
use IlBronza\Products\Http\Controllers\Phase\PhaseEditUpdateController;
use IlBronza\Products\Http\Controllers\Phase\PhaseShowController;
use IlBronza\Products\Http\Controllers\Phase\ProductPhaseIndexController;
use IlBronza\Products\Http\Controllers\Phase\ProductPhaseReorderController;
use IlBronza\Products\Http\Controllers\ProductRelation\ProductRelationEditUpdateController;
use IlBronza\Products\Http\Controllers\ProductRelation\ProductRelationIndexController;
use IlBronza\Products\Http\Controllers\ProductRelation\ProductRelationShowController;
use IlBronza\Products\Http\Controllers\Product\ByOrderProductIndexController;
use IlBronza\Products\Http\Controllers\Product\ProductCurrentController;
use IlBronza\Products\Http\Controllers\Product\ProductDeletionController;
use IlBronza\Products\Http\Controllers\Product\ProductEditUpdateController;
use IlBronza\Products\Http\Controllers\Product\ProductIndexController;
use IlBronza\Products\Http\Controllers\Product\ProductShowController;
use IlBronza\Products\Http\Controllers\Product\ProductTeaserController;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\AccessoryFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByClientProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByOrderRelatedOrderProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByProductRelatedOrderProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByProductRelatedProductRelationFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\RelatedOrderProductPhaseFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\RelatedPhaseFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\AccessoryCrudFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderProductShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\PhaseShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProductRelationEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProductShowFieldsetsParameters;
use IlBronza\Products\Models\Accessory;
use IlBronza\Products\Models\AccessoryProduct;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\Product;
use IlBronza\Products\Models\ProductRelation;
use IlBronza\Products\Providers\RelationshipsManagers\OrderProductRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\OrderRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\PhaseRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\ProductRelationManager;

return [
    'routePrefix' => 'ibProducts',

    'models' => [
        'accessory' => [
            'class' => Accessory::class,
            'table' => 'products__accessories',
            'fieldsGroupsFiles' => [
                'index' => AccessoryFieldsGroupParametersFile::class
            ],
            // 'relationshipsManagerClasses' => [
            //     'show' => ProductRelationManager::class
            // ],
            'parametersFiles' => [
                'crud' => AccessoryCrudFieldsetsParameters::class,
            ],
            'controllers' => [
                'crud' => AccessoryCrudController::class,
                // 'create' => AccessoryCreateStoreController::class,
                // 'edit' => AccessoryEditUpdateController::class,
                // 'destroy' => AccessoryDeletionController::class,
                // 'index' => AccessoryIndexController::class,
                // 'byOrderProductIndex' => ByOrderProductIndexController::class,
                // 'current' => ProductCurrentController::class
            ],
        ],
        'accessoryProduct' => [
            'class' => AccessoryProduct::class,
            'table' => 'products__accessory_products',
            // 'fieldsGroupsFiles' => [
            //     'index' => ProductFieldsGroupParametersFile::class
            // ],
            // 'relationshipsManagerClasses' => [
            //     'show' => ProductRelationManager::class
            // ],
            // 'parametersFiles' => [
            //     'edit' => ProductShowFieldsetsParameters::class,
            //     'show' => ProductShowFieldsetsParameters::class,
            //     'teaser' => ProductShowFieldsetsParameters::class,
            // ],
            'controllers' => [
                // 'crud' => AccessoryCrudController::class,
                // 'create' => AccessoryCreateStoreController::class,
                // 'edit' => AccessoryEditUpdateController::class,
                // 'destroy' => AccessoryDeletionController::class,
                // 'index' => AccessoryIndexController::class,
                // 'byOrderProductIndex' => ByOrderProductIndexController::class,
                // 'current' => ProductCurrentController::class
            ],
        ],
        'product' => [
            'class' => Product::class,
            'table' => 'products__products',
            'fieldsGroupsFiles' => [
                'index' => ProductFieldsGroupParametersFile::class,
                'byClientIndex' => ByClientProductFieldsGroupParametersFile::class
            ],
            'relationshipsManagerClasses' => [
                'show' => ProductRelationManager::class
            ],
            'parametersFiles' => [
                'edit' => ProductShowFieldsetsParameters::class,
                'show' => ProductShowFieldsetsParameters::class,
                'teaser' => ProductShowFieldsetsParameters::class,
            ],
            'controllers' => [
                'show' => ProductShowController::class,
                'teaser' => ProductTeaserController::class,
                'edit' => ProductEditUpdateController::class,
                'destroy' => ProductDeletionController::class,
                'index' => ProductIndexController::class,
                'byOrderProductIndex' => ByOrderProductIndexController::class,
                'current' => ProductCurrentController::class
            ],
        ],
        'phase' => [
            'class' => Phase::class,
            'table' => 'products__phases',
            'fieldsGroupsFiles' => [
                'related' => RelatedPhaseFieldsGroupParametersFile::class
            ],
            'relationshipsManagerClasses' => [
                'show' => PhaseRelationManager::class
            ],
            'parametersFiles' => [
                'show' => PhaseShowFieldsetsParameters::class,
            ],
            'controllers' => [
                'edit' => PhaseEditUpdateController::class,
                'show' => PhaseShowController::class,
                'edit' => OrderEditUpdateController::class,
                'reorder' => ProductPhaseReorderController::class,
                'productPhaseIndex' => ProductPhaseIndexController::class
            ]
        ],
        'order' => [
            'class' => Order::class,
            'table' => 'products__orders',
            'controllers' => [
                'index' => OrderIndexController::class,
                'show' => OrderShowController::class,
                'teaser' => OrderTeaserController::class,
                'destroy' => OrderDeletionController::class,
            ],
            'parametersFiles' => [
                'show' => OrderShowFieldsetsParameters::class,
                'teaser' => OrderShowFieldsetsParameters::class,
                'edit' => OrderEditFieldsetsParameters::class,
            ],
            'relationshipsManagerClasses' => [
                'show' => OrderRelationManager::class
            ],
            'fieldsGroupsFiles' => [

            ]
        ],
        'orderProduct' => [
            'class' => OrderProduct::class,
            'table' => 'products__order_products',
            'controllers' => [
                'show' => OrderProductShowController::class,
                'edit' => OrderProductEditUpdateController::class,
                'byProductIndex' => ByProductOrderProductIndexController::class,
                'byOrderIndex' => ByOrderOrderProductIndexController::class,
            ],
            'parametersFiles' => [
                'show' => OrderProductShowFieldsetsParameters::class,
                'teaser' => OrderProductShowFieldsetsParameters::class,
            ],
            'relationshipsManagerClasses' => [
                'show' => OrderProductRelationManager::class
            ],
            'fieldsGroupsFiles' => [
                'orderRelated' => ByOrderRelatedOrderProductFieldsGroupParametersFile::class,
                'productRelated' => ByProductRelatedOrderProductFieldsGroupParametersFile::class
            ]
        ],
        'orderProductPhase' => [
            'class' => OrderProductPhase::class,
            'table' => 'products__order_product_phases',
            'fieldsGroupsFiles' => [
                'related' => RelatedOrderProductPhaseFieldsGroupParametersFile::class
            ],
            'controllers' => [
                'show' => OrderProductPhaseShowController::class,
                'phaseOrderProductPhaseIndex' => PhaseOrderProductPhaseIndexController::class
            ]
        ],
        'productRelation' => [
            'class' => ProductRelation::class,
            'table' => 'products__product_relations',
            'controllers' => [
                'show' => ProductRelationShowController::class,
                'edit' => ProductRelationEditUpdateController::class,
                'byProductIndex' => ProductRelationIndexController::class,
                'index' => ProductRelationIndexController::class,
            ],
            'parametersFiles' => [
                'edit' => ProductRelationEditFieldsetsParameters::class,
            ],
            'fieldsGroupsFiles' => [
                'productRelated' => ByProductRelatedProductRelationFieldsGroupParametersFile::class
            ],
        ],
        'workstation' => [
            'class' => Workstation::class,
            'table' => 'workstations',
        ]
    ]
];