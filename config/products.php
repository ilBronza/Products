<?php

use IlBronza\Products\Http\Controllers\AccessoryProduct\AccessoryProductEditUpdateController;
use IlBronza\Products\Http\Controllers\AccessoryProduct\AccessoryProductIndexController;
use IlBronza\Products\Http\Controllers\AccessoryProduct\AccessoryProductShowController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryCrudController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryMediaController;
use IlBronza\Products\Http\Controllers\Assignee\AssigneeOrderProductPhaseController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\ByOrderProductOrderProductPhaseIndexController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\ElaboratedByWorkstationOrderProductPhaseIndexController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCompleteController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseDestroyController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseEditUpdateController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseShowController;
use IlBronza\Products\Http\Controllers\OrderProduct\ElaboratedByWorkstationOrderProductIndexController;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductEditUpdateController;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductRestoreController;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductShowController;
use IlBronza\Products\Http\Controllers\OrderProduct\ToElaborateByWorkstationOrderProductIndexController;
use IlBronza\Products\Http\Controllers\Order\ActiveByClientOrderIndexController;
use IlBronza\Products\Http\Controllers\Order\ActiveOrderIndexController;
use IlBronza\Products\Http\Controllers\Order\AllOrderIndexController;
use IlBronza\Products\Http\Controllers\Order\OrderCreateController;
use IlBronza\Products\Http\Controllers\Order\OrderDeletionController;
use IlBronza\Products\Http\Controllers\Order\OrderEditUpdateController;
use IlBronza\Products\Http\Controllers\Order\OrderIndexController;
use IlBronza\Products\Http\Controllers\Order\OrderShowController;
use IlBronza\Products\Http\Controllers\Order\OrderTeaserController;
use IlBronza\Products\Http\Controllers\Packing\PackingDeleteMediaController;
use IlBronza\Products\Http\Controllers\Packing\PackingEditUpdateController;
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
use IlBronza\Products\Http\Controllers\Project\ProjectCreateStoreController;
use IlBronza\Products\Http\Controllers\Project\ProjectDestroyController;
use IlBronza\Products\Http\Controllers\Project\ProjectEditUpdateController;
use IlBronza\Products\Http\Controllers\Project\ProjectIndexController;
use IlBronza\Products\Http\Controllers\Project\ProjectShowController;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\QuotationCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\QuotationEditUpdateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\QuotationrowCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\QuotationrowEditUpdateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableSupplierCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\AccessoryFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\AllOrderFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByClientProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByOrderRelatedOrderProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByProductRelatedAccessoryProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByProductRelatedOrderProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByProductRelatedProductRelationFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\OrderFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\OrderProductRelatedOrderProductPhaseFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ProjectFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\QuotationFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\QuotationRelatedFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\QuotationrowByQuotationFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\QuotationrowFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\QuotationrowRelatedFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\RelatedOrderProductPhaseFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\RelatedPhaseFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableRelatedFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierBaseFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierByOperatorFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierContracttypeFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierRelatedFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SupplierFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\AccessoryCrudFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\AccessoryProductEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderCreateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderProductPhaseEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderProductPhaseShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderProductShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\PackingEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\PhaseEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\PhaseShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProductRelationEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProductShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProjectCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SupplierShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Quotation\QuotationAddQuotationrowIndexController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationCreateStoreController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationDestroyController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationEditUpdateController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationIndexController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationShowController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowAssignSellableSupplierController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowCreateStoreController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowDestroyController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowEditUpdateController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowIndexController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowShowController;
use IlBronza\Products\Http\Controllers\Sellable\SellableCreateStoreController;
use IlBronza\Products\Http\Controllers\Sellable\SellableDestroyController;
use IlBronza\Products\Http\Controllers\Sellable\SellableEditUpdateController;
use IlBronza\Products\Http\Controllers\Sellable\SellableIndexController;
use IlBronza\Products\Http\Controllers\Sellable\SellableShowController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierCreateStoreController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierDestroyController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierEditUpdateController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierIndexController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierShowController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierByCategoryController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierDestroyController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierEditUpdateController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierIndexController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierShowController;
use IlBronza\Products\Models\Accessory;
use IlBronza\Products\Models\AccessoryProduct;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Packing;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\ProductRelation;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Models\Quotations\Project;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\RelationshipsManagers\OrderProductRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\OrderRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\PhaseRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\ProductRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\ProjectRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\QuotationRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\QuotationrowRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\SellableRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\SellableSupplierRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\SupplierRelationManager;

return [
	'routePrefix' => 'ibProducts',

	'sellables' => [
		'enabled' => true,
		// 'models' => [
		//     'vehicle' => [
		//         'class' => Vehicle::class
		//     ],
		//     'contracttype' => [
		//         'class' => Contracttype::class
		//     ]
		// ]
	],

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
				'media' => AccessoryMediaController::class,
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
			'controllers' => [
				'show' => AccessoryProductShowController::class,
				'edit' => AccessoryProductEditUpdateController::class,
				'byProductIndex' => AccessoryProductIndexController::class,
				'index' => AccessoryProductIndexController::class,
			],
			'parametersFiles' => [
				'edit' => AccessoryProductEditFieldsetsParameters::class,
			],
			'fieldsGroupsFiles' => [
				'productRelated' => ByProductRelatedAccessoryProductFieldsGroupParametersFile::class
			],
		],
		'assigneeTarget' => [
			// 'class' => AssigneeTarget::class,
			'table' => 'products___assignee_targets',
			'controllers' => [
				'orderProductPhasesAssignController' => AssigneeOrderProductPhaseController::class,
				// 'show' => AccessoryProductShowController::class,
				// 'edit' => AccessoryProductEditUpdateController::class,
				// 'byProductIndex' => AccessoryProductIndexController::class,
				// 'index' => AccessoryProductIndexController::class,
			],
			'parametersFiles' => [
				// 'edit' => AccessoryProductEditFieldsetsParameters::class,
			],
			'fieldsGroupsFiles' => [
				// 'productRelated' => ByProductRelatedAccessoryProductFieldsGroupParametersFile::class
			],
		],
		'material' => [
			'class' => Material::class,
			'table' => 'products___materials',
			// 'fieldsGroupsFiles' => [
			//     'index' => ProductFieldsGroupParametersFile::class
			// ],
			// 'relationshipsManagerClasses' => [
			//     'show' => ProductRelationManager::class
			// ],
			'parametersFiles' => [
				// 'edit' => PackingEditFieldsetsParameters::class,
				// 'show' => ProductShowFieldsetsParameters::class,
				// 'teaser' => ProductShowFieldsetsParameters::class,
			],
			'controllers' => [
				// 'crud' => AccessoryCrudController::class,
				// 'create' => AccessoryCreateStoreController::class,
				// 'edit' => PackingEditUpdateController::class,
				// 'deleteMedia' => PackingDeleteMediaController::class,
				// 'destroy' => AccessoryDeletionController::class,
				// 'index' => AccessoryIndexController::class,
				// 'byOrderProductIndex' => ByOrderProductIndexController::class,
				// 'current' => ProductCurrentController::class
			],
		],
		'packing' => [
			'class' => Packing::class,
			'table' => 'products___packing',
			// 'fieldsGroupsFiles' => [
			//     'index' => ProductFieldsGroupParametersFile::class
			// ],
			// 'relationshipsManagerClasses' => [
			//     'show' => ProductRelationManager::class
			// ],
			'parametersFiles' => [
				'edit' => PackingEditFieldsetsParameters::class,
				// 'show' => ProductShowFieldsetsParameters::class,
				// 'teaser' => ProductShowFieldsetsParameters::class,
			],
			'controllers' => [
				// 'crud' => AccessoryCrudController::class,
				// 'create' => AccessoryCreateStoreController::class,
				'edit' => PackingEditUpdateController::class,
				'deleteMedia' => PackingDeleteMediaController::class,
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
				'edit' => PhaseEditFieldsetsParameters::class,
			],
			'controllers' => [
				'edit' => PhaseEditUpdateController::class,
				'update' => PhaseEditUpdateController::class,
				'show' => PhaseShowController::class,
				'reorder' => ProductPhaseReorderController::class,
				'productPhaseIndex' => ProductPhaseIndexController::class
			]
		],
		'order' => [
			'class' => Order::class,
			'table' => 'products__orders',
			'controllers' => [
				'create' => OrderCreateController::class,
				'active' => ActiveOrderIndexController::class,
				'activeByClient' => ActiveByClientOrderIndexController::class,
				'index' => OrderIndexController::class,
				'all' => AllOrderIndexController::class,
				'show' => OrderShowController::class,
				'edit' => OrderEditUpdateController::class,
				'teaser' => OrderTeaserController::class,
				'destroy' => OrderDeletionController::class,
			],
			'parametersFiles' => [
				'create' => OrderCreateFieldsetsParameters::class,
				'show' => OrderShowFieldsetsParameters::class,
				'teaser' => OrderShowFieldsetsParameters::class,
				'edit' => OrderEditFieldsetsParameters::class,
			],
			'relationshipsManagerClasses' => [
				'show' => OrderRelationManager::class
			],
			'fieldsGroupsFiles' => [
				'active' => ActiveOrdersFieldsGroupParametersFile::class,
				'related' => ActiveOrdersFieldsGroupParametersFile::class,
				'index' => OrderFieldsGroupParametersFile::class,
				'all' => AllOrderFieldsGroupParametersFile::class,
			]
		],
		'orderrow' => [
			'class' => Orderrow::class,
			'table' => 'products__orderrows',
			'controllers' => [
				'index' => OrderrowIndexController::class,
				'create' => OrderrowCreateStoreController::class,
				'store' => OrderrowCreateStoreController::class,
				'show' => OrderrowShowController::class,
				'edit' => OrderrowEditUpdateController::class,
				'update' => OrderrowEditUpdateController::class,
				'destroy' => OrderrowDestroyController::class,
			],
			'parametersFiles' => [
				'create' => OrderrowCreateFieldsetsParameters::class,
				'show' => OrderrowShowFieldsetsParameters::class,
				'edit' => OrderrowEditFieldsetsParameters::class,
			],
			'relationshipsManagerClasses' => [
				'show' => OrderrowRelationManager::class
			],
			'fieldsGroupsFiles' => [
				'related' => OrderrowRelatedFieldsGroupParametersFile::class,
				'index' => OrderrowFieldsGroupParametersFile::class,
			]
		],
		'orderProduct' => [
			'class' => OrderProduct::class,
			'table' => 'products__order_products',
			'controllers' => [
				'restore' => OrderProductRestoreController::class,
				'show' => OrderProductShowController::class,
				'edit' => OrderProductEditUpdateController::class,
				'elaboratedByWorkstation' => ElaboratedByWorkstationOrderProductIndexController::class,
				'toElaboratebyWorkstation' => ToElaborateByWorkstationOrderProductIndexController::class,
				'byProductIndex' => ByProductOrderProductIndexController::class,
				'byOrderIndex' => ByOrderOrderProductIndexController::class,
			],
			'timingEstimator' => TimingEstimatorOrderProduct::class,
			'parametersFiles' => [
				'show' => OrderProductShowFieldsetsParameters::class,
				'teaser' => OrderProductShowFieldsetsParameters::class,
				'edit' => OrderProductShowFieldsetsParameters::class,
			],
			'relationshipsManagerClasses' => [
				'show' => OrderProductRelationManager::class
			],
			'fieldsGroupsFiles' => [
				'workstationRelated' => ByWorkstationRelatedOrderProductFieldsGroupParametersFile::class,
				'orderRelated' => ByOrderRelatedOrderProductFieldsGroupParametersFile::class,
				'productRelated' => ByProductRelatedOrderProductFieldsGroupParametersFile::class
			]
		],
		'orderProductPhase' => [
			'class' => OrderProductPhase::class,
			'table' => 'products__order_product_phases',
			'fieldsGroupsFiles' => [
				'related' => RelatedOrderProductPhaseFieldsGroupParametersFile::class,
				'toElaborate' => ToElaborateOrderProductPhaseFieldsGroupParametersFile::class,
				'orderProductRelated' => OrderProductRelatedOrderProductPhaseFieldsGroupParametersFile::class,
				'elaborated' => RelatedOrderProductPhaseFieldsGroupParametersFile::class
			],
			'parametersFiles' => [
				'edit' => OrderProductPhaseEditFieldsetsParameters::class,
				'show' => OrderProductPhaseShowFieldsetsParameters::class,
			],
			'timingEstimator' => TimingEstimatorOrderProductPhase::class,
			'controllers' => [
				'toElaboratebyWorkstation' => ToElaborateByWorkstationOrderProductPhaseIndexController::class,
				'elaboratedByWorkstation' => ElaboratedByWorkstationOrderProductPhaseIndexController::class,
				'byOrderProductIndex' => ByOrderProductOrderProductPhaseIndexController::class,
				'show' => OrderProductPhaseShowController::class,
				'edit' => OrderProductPhaseEditUpdateController::class,
				'phaseOrderProductPhaseIndex' => PhaseOrderProductPhaseIndexController::class,
				'complete' => OrderProductPhaseCompleteController::class,
				'reopen' => OrderProductPhaseReopenController::class,
				'destroy' => OrderProductPhaseDestroyController::class,
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
		'project' => [
			'table' => 'products__quotations__projects',
			'class' => Project::class,
			'fieldsGroupsFiles' => [
				'index' => ProjectFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'show' => ProjectRelationManager::class
			],
			'parametersFiles' => [
				'create' => ProjectCreateStoreFieldsetsParameters::class,
				'show' => ProjectCreateStoreFieldsetsParameters::class,
			],
			'controllers' => [
				'index' => ProjectIndexController::class,
				'create' => ProjectCreateStoreController::class,
				'store' => ProjectCreateStoreController::class,
				'show' => ProjectShowController::class,
				'edit' => ProjectEditUpdateController::class,
				'update' => ProjectEditUpdateController::class,
				'destroy' => ProjectDestroyController::class,
			]
		],
		'quotation' => [
			'table' => 'products__quotations__quotations',
			'class' => \App\Models\ProjectSpecific\Quotation::class,
			'fieldsGroupsFiles' => [
				'index' => QuotationFieldsGroupParametersFile::class,
				'related' => QuotationRelatedFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'show' => QuotationRelationManager::class
			],
			'parametersFiles' => [
				'create' => QuotationCreateStoreFieldsetsParameters::class,
				'show' => QuotationEditUpdateFieldsetsParameters::class,
				'edit' => QuotationEditUpdateFieldsetsParameters::class,
			],
			'controllers' => [
				'addQuotationrow' => QuotationAddQuotationrowIndexController::class,
				'index' => QuotationIndexController::class,
				'create' => QuotationCreateStoreController::class,
				'store' => QuotationCreateStoreController::class,
				'show' => QuotationShowController::class,
				'edit' => QuotationEditUpdateController::class,
				'update' => QuotationEditUpdateController::class,
				'destroy' => QuotationDestroyController::class,
			]
		],
		'quotationrow' => [
			'table' => 'products__quotations__quotationrows',
			'class' => Quotationrow::class,
			'fieldsGroupsFiles' => [
				'byQuotation' => QuotationrowByQuotationFieldsGroupParametersFile::class,
				'index' => QuotationrowFieldsGroupParametersFile::class,
				'related' => QuotationrowRelatedFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'show' => QuotationrowRelationManager::class
			],
			'parametersFiles' => [
				'create' => QuotationrowCreateStoreFieldsetsParameters::class,
				'edit' => QuotationrowEditUpdateFieldsetsParameters::class,
				'show' => QuotationrowCreateStoreFieldsetsParameters::class
			],
			'controllers' => [
				'assignSellableSupplier' => QuotationrowAssignSellableSupplierController::class,
				'index' => QuotationrowIndexController::class,
				'create' => QuotationrowCreateStoreController::class,
				'store' => QuotationrowCreateStoreController::class,
				'show' => QuotationrowShowController::class,
				'edit' => QuotationrowEditUpdateController::class,
				'update' => QuotationrowEditUpdateController::class,
				'destroy' => QuotationrowDestroyController::class,
			]
		],
		'quotationrowCandidates' => [
			'table' => 'products__quotations__quotationrows_candidates',
			'class' => QuotationrowCandidate::class,
		],
		'sellable' => [
			'table' => 'products__sellables__sellables',
			'class' => \App\Models\ProjectSpecific\Sellable::class,
			'fieldsGroupsFiles' => [
				'index' => SellableFieldsGroupParametersFile::class,
				'related' => SellableRelatedFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'show' => SellableRelationManager::class
			],
			'parametersFiles' => [
				'create' => SellableCreateStoreFieldsetsParameters::class,
				'show' => SellableCreateStoreFieldsetsParameters::class
			],
			'controllers' => [
				'index' => SellableIndexController::class,
				'create' => SellableCreateStoreController::class,
				'store' => SellableCreateStoreController::class,
				'show' => SellableShowController::class,
				'edit' => SellableEditUpdateController::class,
				'update' => SellableEditUpdateController::class,
				'destroy' => SellableDestroyController::class,
			]
		],
		'sellableSupplier' => [
			'table' => 'products__sellables__sellable_suppliers',
			'class' => SellableSupplier::class,
			'fieldsGroupsFiles' => [
				'contracttype' => SellableSupplierContracttypeFieldsGroupParametersFile::class,
				'byOperator' => SellableSupplierByOperatorFieldsGroupParametersFile::class,
				'base' => SellableSupplierBaseFieldsGroupParametersFile::class,
				'index' => SellableSupplierFieldsGroupParametersFile::class,
				'related' => SellableSupplierRelatedFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'show' => SellableSupplierRelationManager::class
			],
			'parametersFiles' => [
				'contracttype' => SellableSupplierContracttypeEditUpdateFieldsetsParameters::class,
				'create' => SellableSupplierCreateStoreFieldsetsParameters::class,
				'show' => SellableSupplierCreateStoreFieldsetsParameters::class
			],
			'controllers' => [
				'index' => SellableSupplierIndexController::class,
				'create' => SellableSupplierCreateStoreController::class,
				'store' => SellableSupplierCreateStoreController::class,
				'show' => SellableSupplierShowController::class,
				'edit' => SellableSupplierEditUpdateController::class,
				'update' => SellableSupplierEditUpdateController::class,
				'destroy' => SellableSupplierDestroyController::class,
			]
		],
		'sellableOption' => [
			'table' => 'products__sellables__sellable_options',
			'class' => SellableOption::class,
		],
		'sellableRelation' => [
			'table' => 'products__sellables__sellable_relations',
			'class' => SellableRelation::class,
		],
		'supplier' => [
			'table' => 'products__sellables__suppliers',
			'class' => Supplier::class,
			'controllers' => [
				'byCategory' => SupplierByCategoryController::class,
				'index' => SupplierIndexController::class,
				'edit' => SupplierEditUpdateController::class,
				'show' => SupplierShowController::class,
				'destroy' => SupplierDestroyController::class,
			],
			'relationshipsManagerClasses' => [
				'show' => SupplierRelationManager::class
			],
			'parametersFiles' => [
				'show' => SupplierShowFieldsetsParameters::class,
			],
			'fieldsGroupsFiles' => [
				'index' => SupplierFieldsGroupParametersFile::class
			],
		],
		'workstation' => [
			'class' => Workstation::class,
			'table' => 'workstations',
		]
	]
];