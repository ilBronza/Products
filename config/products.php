<?php

use App\Http\Controllers\PackageOverriding\Products\FieldsParameters\ClientAreaProductFieldsGroupParametersFile;
use IlBronza\Operators\Helpers\OperatorPricesCreatorHelper;
use IlBronza\Products\Http\Controllers\AccessoryProduct\AccessoryProductEditUpdateController;
use IlBronza\Products\Http\Controllers\AccessoryProduct\AccessoryProductIndexController;
use IlBronza\Products\Http\Controllers\AccessoryProduct\AccessoryProductShowController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryCreateByParentController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryCreateStoreController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryCrudController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryDeleteMediaController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryDestroyController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryEditUpdateController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryIndexController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryMediaController;
use IlBronza\Products\Http\Controllers\Accessory\AccessoryShowController;
use IlBronza\Products\Http\Controllers\Assignee\AssigneeOrderProductPhaseController;
use IlBronza\Products\Http\Controllers\ClientArea\ClientAreaOrderIndexController;
use IlBronza\Products\Http\Controllers\Clients\ClientIndexController;
use IlBronza\Products\Http\Controllers\Finishings\FinishingCreateStoreController;
use IlBronza\Products\Http\Controllers\Finishings\FinishingEditUpdateController;
use IlBronza\Products\Http\Controllers\Finishings\FinishingIndexController;
use IlBronza\Products\Http\Controllers\Finishings\FinishingShowController;
use IlBronza\Products\Http\Controllers\Generals\DashboardController;
use IlBronza\Products\Http\Controllers\Materials\MaterialCreateStoreController;
use IlBronza\Products\Http\Controllers\Materials\MaterialDestroyController;
use IlBronza\Products\Http\Controllers\Materials\MaterialEditUpdateController;
use IlBronza\Products\Http\Controllers\Materials\MaterialIndexController;
use IlBronza\Products\Http\Controllers\Materials\MaterialShowController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\ByOrderProductOrderProductPhaseIndexController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\ElaboratedByWorkstationOrderProductPhaseIndexController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseCompleteController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseDestroyController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseEditUpdateController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseReopenController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\OrderProductPhaseShowController;
use IlBronza\Products\Http\Controllers\OrderProductPhase\ToElaborateByWorkstationOrderProductPhaseIndexController;
use IlBronza\Products\Http\Controllers\OrderProduct\ByOrderOrderProductIndexController;
use IlBronza\Products\Http\Controllers\OrderProduct\ByProductOrderProductIndexController;
use IlBronza\Products\Http\Controllers\OrderProduct\ClientAreaOrderProductIndexController;
use IlBronza\Products\Http\Controllers\OrderProduct\ElaboratedByWorkstationOrderProductIndexController;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductEditUpdateController;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductRestoreController;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductShowController;
use IlBronza\Products\Http\Controllers\OrderProduct\ToElaborateByWorkstationOrderProductIndexController;
use IlBronza\Products\Http\Controllers\Order\ActiveByClientOrderIndexController;
use IlBronza\Products\Http\Controllers\Order\ActiveOrderIndexController;
use IlBronza\Products\Http\Controllers\Order\AllOrderIndexController;
use IlBronza\Products\Http\Controllers\Order\AttachClientOperatorsToOrderrowsController;
use IlBronza\Products\Http\Controllers\Order\OrderAddOrderrowIndexByTableController;
use IlBronza\Products\Http\Controllers\Order\OrderAddOrderrowIndexController;
use IlBronza\Products\Http\Controllers\Order\OrderBulkEditUpdateController;
use IlBronza\Products\Http\Controllers\Order\OrderChangeClientController;
use IlBronza\Products\Http\Controllers\Order\OrderCreateController;
use IlBronza\Products\Http\Controllers\Order\OrderDeletionController;
use IlBronza\Products\Http\Controllers\Order\OrderEditUpdateController;
use IlBronza\Products\Http\Controllers\Order\OrderFreezeController;
use IlBronza\Products\Http\Controllers\Order\OrderIndexController;
use IlBronza\Products\Http\Controllers\Order\OrderReplicateOrderrowController;
use IlBronza\Products\Http\Controllers\Order\OrderShowController;
use IlBronza\Products\Http\Controllers\Order\OrderTeaserController;
use IlBronza\Products\Http\Controllers\Order\OrderTimelineController;
use IlBronza\Products\Http\Controllers\Order\ResetOrderRowsIndexesController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowAssignSellableSupplierController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowBulkEditUpdateController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowBySupplierIndexController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowCreateStoreController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowDestroyController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowEditUpdateController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowFindOrAssociateSupplierController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowHistoryController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowIndexController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowReorderController;
use IlBronza\Products\Http\Controllers\Orderrow\OrderrowShowController;
use IlBronza\Products\Http\Controllers\Packing\PackingDeleteMediaController;
use IlBronza\Products\Http\Controllers\Packing\PackingEditUpdateController;
use IlBronza\Products\Http\Controllers\Phase\PhaseCreateStoreByProductController;
use IlBronza\Products\Http\Controllers\Phase\PhaseEditUpdateController;
use IlBronza\Products\Http\Controllers\Phase\PhaseShowController;
use IlBronza\Products\Http\Controllers\Phase\ProductPhaseIndexController;
use IlBronza\Products\Http\Controllers\Phase\ProductPhaseReorderController;
use IlBronza\Products\Http\Controllers\ProductRelation\ProductRelationCreateStoreController;
use IlBronza\Products\Http\Controllers\ProductRelation\ProductRelationEditUpdateController;
use IlBronza\Products\Http\Controllers\ProductRelation\ProductRelationIndexController;
use IlBronza\Products\Http\Controllers\ProductRelation\ProductRelationShowController;
use IlBronza\Products\Http\Controllers\Product\ByOrderProductIndexController;
use IlBronza\Products\Http\Controllers\Product\ClientAreaProductIndexController;
use IlBronza\Products\Http\Controllers\Product\ProductCalculatePricesController;
use IlBronza\Products\Http\Controllers\Product\ProductCreateController;
use IlBronza\Products\Http\Controllers\Product\ProductCurrentController;
use IlBronza\Products\Http\Controllers\Product\ProductDeletionController;
use IlBronza\Products\Http\Controllers\Product\ProductEditUpdateController;
use IlBronza\Products\Http\Controllers\Product\ProductIndexController;
use IlBronza\Products\Http\Controllers\Product\ProductReorderController;
use IlBronza\Products\Http\Controllers\Product\ProductShowController;
use IlBronza\Products\Http\Controllers\Product\ProductTeaserController;
use IlBronza\Products\Http\Controllers\Project\ProjectCreateStoreController;
use IlBronza\Products\Http\Controllers\Project\ProjectDestroyController;
use IlBronza\Products\Http\Controllers\Project\ProjectEditUpdateController;
use IlBronza\Products\Http\Controllers\Project\ProjectIndexController;
use IlBronza\Products\Http\Controllers\Project\ProjectShowController;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\AccessoryFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ActiveOrdersFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\AllOrderFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByClientProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByOrderRelatedOrderProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByProductRelatedAccessoryProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByProductRelatedOrderProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByProductRelatedProductRelationFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ByWorkstationRelatedOrderProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ClientAreaOrderFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ClientAreaOrderProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\FinishingFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\MaterialFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\OperatorRowsByContainerFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\OrderChildrenFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\OrderFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\OrderProductRelatedOrderProductPhaseFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\OrderrowFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\OrderrowRelatedFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ProductFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\ProductRowsByContainerFieldsGroupParametersFile;
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
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierHotelFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierRelatedFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierRentFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SellableSupplierVehicletypeFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\SupplierFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\VehicleSellableRelatedFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\VehicleSellableSupplierRelatedFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\FieldsGroups\WorkstationFieldsGroupParametersFile;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\AccessoryCrudFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\AccessoryEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\AccessoryProductEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ClientAreaOrderProductEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\FinishingCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\MaterialCreateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\MaterialEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderBulkEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderChangeClientFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderCreateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderProductPhaseEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderProductPhaseShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderProductShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderrowBulkEditUpdateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\OrderrowEditUpdateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\PackingEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\PhaseCreateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\PhaseEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\PhaseShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProductRelationCreateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProductRelationEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProductShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\ProjectCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\QuotationCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\QuotationEditUpdateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\QuotationrowCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\QuotationrowEditUpdateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableSupplierContracttypeEditUpdateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableSupplierCreateStoreBySellableFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableSupplierCreateStoreBySupplierFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableSupplierCreateStoreFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableSupplierHotelEditUpdateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SellableSupplierRentEditUpdateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\SupplierShowFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\WorkstationCreateFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Providers\Fieldsets\WorkstationEditFieldsetsParameters;
use IlBronza\Products\Http\Controllers\Quotation\QuotationAddQuotationrowIndexByTableController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationAddQuotationrowIndexController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationChangeClientController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationConvertToOrderController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationCreateStoreController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationCurrentController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationDestinationCreateStoreController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationDestroyController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationDuplicateController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationEditUpdateController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationIndexController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationReplicateRowController;
use IlBronza\Products\Http\Controllers\Quotation\QuotationShowController;
use IlBronza\Products\Http\Controllers\Quotation\ResetQuotationRowsIndexesController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowAssignSellableSupplierController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowCreateStoreController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowDestroyController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowEditUpdateController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowFindOrAssociateSupplierController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowHistoryController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowIndexController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowReorderController;
use IlBronza\Products\Http\Controllers\Quotationrow\QuotationrowShowController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierCreateStoreBySellableController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierCreateStoreBySupplierController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierCreateStoreController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierDestroyController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierEditUpdateController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierIndexController;
use IlBronza\Products\Http\Controllers\SellableSupplier\SellableSupplierShowController;
use IlBronza\Products\Http\Controllers\Sellable\SellableCreateStoreController;
use IlBronza\Products\Http\Controllers\Sellable\SellableDestroyController;
use IlBronza\Products\Http\Controllers\Sellable\SellableEditUpdateController;
use IlBronza\Products\Http\Controllers\Sellable\SellableIndexController;
use IlBronza\Products\Http\Controllers\Sellable\SellableShowController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierByCategoryController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierDestroyController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierEditUpdateController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierIndexController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierShowController;
use IlBronza\Products\Http\Controllers\Supplier\SupplierTimelineController;
use IlBronza\Products\Http\Controllers\Timelines\GlobalOrderTimelineController;
use IlBronza\Products\Http\Controllers\Timelines\GlobalSellableTimelineController;
use IlBronza\Products\Http\Controllers\Timelines\GlobalSupplierTimelineController;
use IlBronza\Products\Http\Controllers\Workstations\WorkstationCreateStoreController;
use IlBronza\Products\Http\Controllers\Workstations\WorkstationDestroyController;
use IlBronza\Products\Http\Controllers\Workstations\WorkstationEditUpdateController;
use IlBronza\Products\Http\Controllers\Workstations\WorkstationIndexController;
use IlBronza\Products\Http\Controllers\Workstations\WorkstationShowController;
use IlBronza\Products\Models\Accessory;
use IlBronza\Products\Models\AccessoryProduct;
use IlBronza\Products\Models\Client;
use IlBronza\Products\Models\Finishing;
use IlBronza\Products\Models\Material;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Orderrows\OperatorOrderrow;
use IlBronza\Products\Models\Orderrows\ProductOrderrow;
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
use IlBronza\Products\Models\Workstation;
use IlBronza\Products\Providers\Helpers\OrderProductPhases\OrderProductPhaseCheckCompletionHelper;
use IlBronza\Products\Providers\Helpers\OrderProductPhases\OrderProductPhaseCompletionHelper;
use IlBronza\Products\Providers\Helpers\OrderProductPhases\OrderProductPhaseQuantityHelper;
use IlBronza\Products\Providers\Helpers\OrderProductPhases\OrderProductPhaseUncompletionHelper;
use IlBronza\Products\Providers\Helpers\OrderProductPhases\OrderProductQuantityHelper;
use IlBronza\Products\Providers\Helpers\OrderProducts\OrderProductCompletionHelper;
use IlBronza\Products\Providers\Helpers\Orders\OrderCompletionHelper;
use IlBronza\Products\Providers\Helpers\PriceCreatorHelpers\ProductPricesCreatorHelper;
use IlBronza\Products\Providers\Helpers\QuotationOrder\OrderFreezerHelper;
use IlBronza\Products\Providers\Helpers\QuotationOrder\QuotationDuplicatorHelper;
use IlBronza\Products\Providers\Helpers\QuotationOrder\QuotationFreezerHelper;
use IlBronza\Products\Providers\Helpers\Quotations\QuotationToOrderConverterHelper;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowsSellableSupplierAssociatorHelper;
use IlBronza\Products\Providers\Helpers\SellableSuppliers\SellableSupplierFindBySellableHelper;
use IlBronza\Products\Providers\Helpers\Sellables\TargetCreators\MaterialFromSellableCreatorHelper;
use IlBronza\Products\Providers\Helpers\Suppliers\SupplierBulkCreator;
use IlBronza\Products\Providers\RelationshipsManagers\AccessoryRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\OrderProductRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\OrderRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\PhaseRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\ProductRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\ProjectRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\QuotationEditRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\QuotationRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\QuotationrowRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\SellableRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\SellableSupplierRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\SupplierRelationManager;

return [
	'routePrefix' => 'ibProducts',

	'manageDiaries' => false,
	'manageReimbursements' => false,
	'manageHotels' => false,

	'datatableFieldWidths' => [
		'products' => [
			'datatableFieldShortDescription' => '20em',

			'datatableFieldProduct' => '18em'
		],
		'orderProducts' => [
			'datatableFieldPiecesDone' => '2em'
		],
		'orders' => [
			'datatableFieldOrder' => '7.3em'
		],
		'orderrows' => [
			'datatableFieldAssociateSupplier' => '2em',
			'datatableFieldAssignSellableSupplier' => '2em',
			'datatableFieldAssignBulkSellableSupplier' => '2em'
		],
		'quotations' => [
			'datatableFieldQuotation' => '7.3em'
		],
		'quotationrows' => [
			'datatableFieldAssociateSupplier' => '2em',
			'datatableFieldAssignSellableSupplier' => '2em',
			'datatableFieldAssignBulkSellableSupplier' => '2em'
		],

		'sellables' => [
			'datatableFieldSellable' => '18em'
		],
		'suppliers' => [
			'datatableFieldSuppliers' => '12em'
		],

		'projects' => [
			'datatableFieldProject' => '18em'
		],
		'sellableSuppliers' => [
			'datatableFieldFindOrAssociateSupplier' => '2em'
		],
		'workstations' => [
			'datatableFieldLabel' => '2em'
		]
	],

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

	'dashboard' => [
		'controller' => DashboardController::class
	],

	'models' => [
		'accessory' => [
			'class' => Accessory::class,
			'table' => 'products__accessories',
			'fieldsGroupsFiles' => [
				'index' => AccessoryFieldsGroupParametersFile::class,
				'related' => AccessoryFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
			    'show' => AccessoryRelationManager::class
			],
			'parametersFiles' => [
				'create' => AccessoryCrudFieldsetsParameters::class,
				'edit' => AccessoryEditFieldsetsParameters::class,
			],
			'controllers' => [
				'index' => AccessoryIndexController::class,
				'media' => AccessoryMediaController::class,
				'create' => AccessoryCreateStoreController::class,
				'createByParent' => AccessoryCreateByParentController::class,
				'store' => AccessoryCreateStoreController::class,
				'show' => AccessoryShowController::class,
				'edit' => AccessoryEditUpdateController::class,
				'update' => AccessoryEditUpdateController::class,
				'destroy' => AccessoryDestroyController::class,
				'deleteMedia' => AccessoryMediaController::class,
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
		'client' => [
			'class' => Client::class,
			'controllers' => [
				'index' => ClientIndexController::class,
			],
		],
		'customOrderrows' => [
			'operatorOrderrow' => [
				'class' => OperatorOrderrow::class,
			],
			'productOrderrow' => [
				'class' => ProductOrderrow::class,
			]
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
		'finishing' => [
			'class' => Finishing::class,
			'table' => 'products___finishings',
			'fieldsGroupsFiles' => [
				'index' => FinishingFieldsGroupParametersFile::class
			],
			'parametersFiles' => [
				'create' => FinishingCreateStoreFieldsetsParameters::class,
			],
			'controllers' => [
				'index' => FinishingIndexController::class,
				'create' => FinishingCreateStoreController::class,
				'store' => FinishingCreateStoreController::class,
				'show' => FinishingShowController::class,
				'edit' => FinishingEditUpdateController::class,
				'update' => FinishingEditUpdateController::class,
				'destroy' => FinishingEditUpdateController::class,
			]
		],
		'material' => [
			'class' => Material::class,
			'table' => 'products___materials',
			'fieldsGroupsFiles' => [
			    'index' => MaterialFieldsGroupParametersFile::class
			],
			// 'relationshipsManagerClasses' => [
			//     'show' => ProductRelationManager::class
			// ],
			'parametersFiles' => [
				'edit' => MaterialEditFieldsetsParameters::class,
				'create' => MaterialCreateFieldsetsParameters::class,
				'show' => MaterialEditFieldsetsParameters::class,
			],
			'controllers' => [
				'create' => MaterialCreateStoreController::class,
				'store' => MaterialCreateStoreController::class,
				'show' => MaterialShowController::class,
				'edit' => MaterialEditUpdateController::class,
				'update' => MaterialEditUpdateController::class,
				'destroy' => MaterialDestroyController::class,
				'index' => MaterialIndexController::class,
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
			'automaticUpdatesPrices' => true,
			'fieldsGroupsFiles' => [
				'index' => ProductFieldsGroupParametersFile::class,
				'byClientIndex' => ByClientProductFieldsGroupParametersFile::class,
				'clientArea' => ClientAreaProductFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'show' => ProductRelationManager::class
			],
			'parametersFiles' => [
				'create' => ProductShowFieldsetsParameters::class,
				'edit' => ProductShowFieldsetsParameters::class,
				'show' => ProductShowFieldsetsParameters::class,
				'teaser' => ProductShowFieldsetsParameters::class,
				'clientArea' => [
					'edit' => ClientAreaProductEditFieldsetsParameters::class,
				]
			],
			'controllers' => [
				'create' => ProductCreateController::class,
				'store' => ProductCreateController::class,
				'show' => ProductShowController::class,
				'teaser' => ProductTeaserController::class,
				'edit' => ProductEditUpdateController::class,
				'destroy' => ProductDeletionController::class,
				'index' => ProductIndexController::class,
				'byOrderProductIndex' => ByOrderProductIndexController::class,
				'calculatePrices' => ProductCalculatePricesController::class,
				'current' => ProductCurrentController::class,
				'clientArea' => ClientAreaProductIndexController::class,
			],
			'helpers' => [
				'sellableSupplierPricesCreator' => ProductPricesCreatorHelper::class
			]
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
				'create' => PhaseCreateFieldsetsParameters::class,
				'show' => PhaseShowFieldsetsParameters::class,
				'edit' => PhaseEditFieldsetsParameters::class,
			],
			'controllers' => [
				'createByProduct' => PhaseCreateStoreByProductController::class,
				'edit' => PhaseEditUpdateController::class,
				'store' => PhaseCreateStoreByProductController::class,
				'update' => PhaseEditUpdateController::class,
				'show' => PhaseShowController::class,
				'reorder' => ProductPhaseReorderController::class,
				'productPhaseIndex' => ProductPhaseIndexController::class
			]
		],
		'order' => [
			'class' => Order::class,
			'table' => 'products__orders',
			'usesHours' => false,
			'canHaveChildren' => false,
			'hasGantt' => false,
			'helpers' => [
				'freezerHelper' => OrderFreezerHelper::class,
				'completionHelper' => OrderCompletionHelper::class
			],
			'controllers' => [
				'bulkEdit' => OrderBulkEditUpdateController::class,
				'bulkUpdate' => OrderBulkEditUpdateController::class,
				'changeClient' => OrderChangeClientController::class,
				'clientArea' => ClientAreaOrderIndexController::class,
				'addOrderrow' => OrderAddOrderrowIndexController::class,
				'addOrderrowsByTable' => OrderAddOrderrowIndexByTableController::class,
				'resetOrderRowsIndexes' => ResetOrderRowsIndexesController::class,
				'attachClientOperatorsToOrderrows' => AttachClientOperatorsToOrderrowsController::class,
				'replicateLastRow' => OrderReplicateOrderrowController::class,
				'create' => OrderCreateController::class,
				'store' => OrderCreateController::class,
				'active' => ActiveOrderIndexController::class,
				'activeByClient' => ActiveByClientOrderIndexController::class,
				'index' => OrderIndexController::class,
				'freeze' => OrderFreezeController::class,
				'all' => AllOrderIndexController::class,
				'show' => OrderShowController::class,
				'edit' => OrderEditUpdateController::class,
				'teaser' => OrderTeaserController::class,
				'destroy' => OrderDeletionController::class,
				'timeline' => OrderTimelineController::class,
				'globalTimeline' => GlobalOrderTimelineController::class,
			],
			'parametersFiles' => [
				'create' => OrderCreateFieldsetsParameters::class,
				'show' => OrderShowFieldsetsParameters::class,
				'teaser' => OrderShowFieldsetsParameters::class,
				'edit' => OrderEditFieldsetsParameters::class,
				'bulkEdit' => OrderBulkEditFieldsetsParameters::class,

				'changeClient' => OrderChangeClientFieldsetsParameters::class,
			],
			'relationshipsManagerClasses' => [
				'show' => OrderRelationManager::class
			],
			'fieldsGroupsFiles' => [
				'active' => ActiveOrdersFieldsGroupParametersFile::class,
				'related' => ActiveOrdersFieldsGroupParametersFile::class,
				'index' => OrderFieldsGroupParametersFile::class,
				'children' => OrderChildrenFieldsGroupParametersFile::class,
				'clientArea' => ClientAreaOrderFieldsGroupParametersFile::class,
				'all' => AllOrderFieldsGroupParametersFile::class,
			],
		],
		'orderrow' => [
			'class' => Orderrow::class,
			'table' => 'products__orderrows',
			'controllers' => [
				'history' => OrderrowHistoryController::class,
				'reorder' => OrderrowReorderController::class,
				'assignSellableSupplier' => OrderrowAssignSellableSupplierController::class,
				'findOrAssociateSupplier' => OrderrowFindOrAssociateSupplierController::class,
				'index' => OrderrowIndexController::class,
				'bySupplier' => OrderrowBySupplierIndexController::class,
				'create' => OrderrowCreateStoreController::class,
				'store' => OrderrowCreateStoreController::class,
				'show' => OrderrowShowController::class,
				'edit' => OrderrowEditUpdateController::class,
				'update' => OrderrowEditUpdateController::class,
				'destroy' => OrderrowDestroyController::class,

				'bulkEdit' => OrderrowBulkEditUpdateController::class
			],
			'parametersFiles' => [
				'create' => OrderrowCreateFieldsetsParameters::class,
				'show' => OrderrowShowFieldsetsParameters::class,
				'edit' => OrderrowEditUpdateFieldsetsParameters::class,
				'bulkEdit' => OrderrowBulkEditUpdateFieldsetsParameters::class,
			],
			'relationshipsManagerClasses' => [
				'show' => OrderrowRelationManager::class
			],
			'fieldsGroupsFiles' => [
				'related' => OrderrowRelatedFieldsGroupParametersFile::class,
				'index' => OrderrowFieldsGroupParametersFile::class,
				'operatorOrderrow' => OperatorRowsByContainerFieldsGroupParametersFile::class,
				'productOrderrow' => ProductRowsByContainerFieldsGroupParametersFile::class
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
				'clientArea' => ClientAreaOrderProductIndexController::class,
			],
			'helpers' => [
				'completionHelper' => OrderProductCompletionHelper::class
			],
			'timingEstimator' => TimingEstimatorOrderProduct::class,
			'parametersFiles' => [
				'show' => OrderProductShowFieldsetsParameters::class,
				'teaser' => OrderProductShowFieldsetsParameters::class,
				'edit' => OrderProductShowFieldsetsParameters::class,

				'clientArea' => [
					'edit' => ClientAreaOrderProductEditFieldsetsParameters::class,
				]
			],
			'relationshipsManagerClasses' => [
				'show' => OrderProductRelationManager::class
			],
			'fieldsGroupsFiles' => [
				'workstationRelated' => ByWorkstationRelatedOrderProductFieldsGroupParametersFile::class,
				'orderRelated' => ByOrderRelatedOrderProductFieldsGroupParametersFile::class,
				'productRelated' => ByProductRelatedOrderProductFieldsGroupParametersFile::class,
				'clientArea' => ClientAreaOrderProductFieldsGroupParametersFile::class
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
			'helpers' => [
				'quantityHelper' => OrderProductPhaseQuantityHelper::class,
				'completionHelper' => OrderProductPhaseCompletionHelper::class,
				'uncompletionHelper' => OrderProductPhaseUncompletionHelper::class,
				'checkCompletionHelper' => OrderProductPhaseCheckCompletionHelper::class
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
				'createByProduct' => ProductRelationCreateStoreController::class,
				'store' => ProductRelationCreateStoreController::class,
				'show' => ProductRelationShowController::class,
				'edit' => ProductRelationEditUpdateController::class,
				'byProductIndex' => ProductRelationIndexController::class,
				'index' => ProductRelationIndexController::class,
			],
			'parametersFiles' => [
				'create' => ProductRelationCreateFieldsetsParameters::class,
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
			'class' => Quotation::class,
			'helpers' => [
				'freezerHelper' => QuotationFreezerHelper::class,
				'duplicate' => QuotationDuplicatorHelper::class,
			],
			'quotationToOrderConverterHelper' => QuotationToOrderConverterHelper::class,
			'fieldsGroupsFiles' => [
				'index' => QuotationFieldsGroupParametersFile::class,
				'related' => QuotationRelatedFieldsGroupParametersFile::class
			],
			'relationshipsManagerClasses' => [
				'edit' => QuotationEditRelationManager::class,
				'show' => QuotationRelationManager::class
			],
			'parametersFiles' => [
				'create' => QuotationCreateStoreFieldsetsParameters::class,
				'show' => QuotationEditUpdateFieldsetsParameters::class,
				'edit' => QuotationEditUpdateFieldsetsParameters::class,
				'changeClient' => OrderChangeClientFieldsetsParameters::class,
			],
			'controllers' => [
				'resetQuotationRowsIndexes' => ResetQuotationRowsIndexesController::class,
				'addQuotationrow' => QuotationAddQuotationrowIndexController::class,
				'addQuotationrowsByTable' => QuotationAddQuotationrowIndexByTableController::class,
				'changeClient' => QuotationChangeClientController::class,
				'replicateLastRow' => QuotationReplicateRowController::class,
				'convertToOrder' => QuotationConvertToOrderController::class,
				'duplicate' => QuotationDuplicateController::class,
				'destination' => QuotationDestinationCreateStoreController::class,
				'index' => QuotationIndexController::class,
				'current' => QuotationCurrentController::class,
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
				'history' => QuotationrowHistoryController::class,
				'reorder' => QuotationrowReorderController::class,
				'assignSellableSupplier' => QuotationrowAssignSellableSupplierController::class,
				'findOrAssociateSupplier' => QuotationrowFindOrAssociateSupplierController::class,
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
			'class' => Sellable::class,
			'automaticUpdatesPrices' => false,
			'availableTypes' => [
				'material',
				'asset'
			],
			'helpers' => [
				'targetCreator' => [
					'material' => MaterialFromSellableCreatorHelper::class,
				],
				'bulkCreator' => SupplierBulkCreator::class
			],
			'fieldsGroupsFiles' => [
				'index' => SellableFieldsGroupParametersFile::class,
				'related' => SellableRelatedFieldsGroupParametersFile::class,
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
				'globalTimeline' => GlobalSellableTimelineController::class,
			]
		],
		'sellableSupplier' => [
			'table' => 'products__sellables__sellable_suppliers',
			'class' => SellableSupplier::class,
			'automaticUpdatesPrices' => false,
			'helpers' => [
				'findBySellableHelper' => SellableSupplierFindBySellableHelper::class,
				'rowSellableSupplierAssociatorHelper' => RowsSellableSupplierAssociatorHelper::class,
			],
			'fieldsGroupsFiles' => [
				'operator' => SellableSupplierContracttypeFieldsGroupParametersFile::class,
				'contracttype' => SellableSupplierContracttypeFieldsGroupParametersFile::class,
				'vehicletype' => SellableSupplierVehicletypeFieldsGroupParametersFile::class,
				'vehicle' => SellableSupplierVehicletypeFieldsGroupParametersFile::class,
				'service' => SellableSupplierRentFieldsGroupParametersFile::class,
				'hotel' => SellableSupplierHotelFieldsGroupParametersFile::class,
				'controlRoom' => SellableSupplierVehicletypeFieldsGroupParametersFile::class,
				'byOperator' => SellableSupplierByOperatorFieldsGroupParametersFile::class,
				'base' => SellableSupplierBaseFieldsGroupParametersFile::class,
				'index' => SellableSupplierFieldsGroupParametersFile::class,
				'related' => SellableSupplierRelatedFieldsGroupParametersFile::class,
				'relatedByType' => [
					'vehicle' => VehicleSellableSupplierRelatedFieldsGroupParametersFile::class
				]
			],
			'relationshipsManagerClasses' => [
				'show' => SellableSupplierRelationManager::class
			],
			'parametersFiles' => [
				'contracttype' => SellableSupplierContracttypeEditUpdateFieldsetsParameters::class,
				'hotel' => SellableSupplierHotelEditUpdateFieldsetsParameters::class,
				'rent' => SellableSupplierRentEditUpdateFieldsetsParameters::class,
				'create' => SellableSupplierCreateStoreFieldsetsParameters::class,
				'createBySupplier' => SellableSupplierCreateStoreBySupplierFieldsetsParameters::class,
				'createBySellable' => SellableSupplierCreateStoreBySellableFieldsetsParameters::class,
				'show' => SellableSupplierCreateStoreFieldsetsParameters::class
			],
			'controllers' => [
				'index' => SellableSupplierIndexController::class,
				'create' => SellableSupplierCreateStoreController::class,
				'createBySupplier' => SellableSupplierCreateStoreBySupplierController::class,
				'createBySellable' => SellableSupplierCreateStoreBySellableController::class,
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
				'timeline' => SupplierTimelineController::class,
				'edit' => SupplierEditUpdateController::class,
				'show' => SupplierShowController::class,
				'destroy' => SupplierDestroyController::class,
				'globalTimeline' => GlobalSupplierTimelineController::class,
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
			'helpers' => [
				'bulkCreator' => SupplierBulkCreator::class
			]
		],
		'workstation' => [
			'class' => Workstation::class,
			'table' => 'products__workstations',
			'controllers' => [
				'index' => WorkstationIndexController::class,
				'create' => WorkstationCreateStoreController::class,
				'store' => WorkstationCreateStoreController::class,
				'edit' => WorkstationEditUpdateController::class,
				'update' => WorkstationEditUpdateController::class,
				'show' => WorkstationShowController::class,
				'destroy' => WorkstationDestroyController::class,
			],
			'fieldsGroupsFiles' => [
				'index' => WorkstationFieldsGroupParametersFile::class
			],
			'parametersFiles' => [
				'create' => WorkstationCreateFieldsetsParameters::class,
				'edit' => WorkstationEditFieldsetsParameters::class,
			],
		]
	]
];