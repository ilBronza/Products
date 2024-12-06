<?php

use App\Http\Controllers\CrudPalletController;
use IlBronza\Products\Http\Controllers\OrderProduct\OrderProductNotesController;
use IlBronza\Products\Products;

Route::group([
	'middleware' => ['web', 'auth'],
	'prefix' => 'products-management',
	'as' => config('products.routePrefix')
], function ()
{
	// Route::resource('pallets', CrudPalletController::class);

	Route::group(['prefix' => 'projects'], function ()
	{
		Route::get('', [Products::getController('project', 'index'), 'index'])->name('projects.index');
		Route::get('create', [Products::getController('project', 'create'), 'create'])->name('projects.create');
		Route::post('', [Products::getController('project', 'store'), 'store'])->name('projects.store');
		Route::get('{project}', [Products::getController('project', 'show'), 'show'])->name('projects.show');
		Route::get('{project}/edit', [Products::getController('project', 'edit'), 'edit'])->name('projects.edit');
		Route::put('{project}', [Products::getController('project', 'edit'), 'update'])->name('projects.update');

		Route::delete('{project}/delete', [Products::getController('project', 'destroy'), 'destroy'])->name('projects.destroy');
	});

	Route::group(['prefix' => 'quotations'], function ()
	{
		Route::get('{quotation}/convert-to-order', [Products::getController('quotation', 'convertToOrder'), 'convertToOrder'])->name('quotations.convertToOrder');

		//DestinationCreateStoreController
		Route::get('{quotation}/create-destination', [config('clients.models.destination.controllers.create'), 'createFromQuotation'])->name('quotations.createDestination');
		//	Route::post('{quotation}/store-destination', [config('clients.models.destination.controllers.create'), 'store'])->name('quotations.storeDestination');

		Route::get('current', [Products::getController('quotation', 'current'), 'index'])->name('quotations.current');

		//QuotationAddQuotationrowIndexController
		Route::post('{quotation}/add-row/type/{type}', [Products::getController('quotation', 'addQuotationrow'), 'addQuotationrow'])->name('quotations.addQuotationrow');

		//QuotationAddQuotationrowIndexController
		Route::post('{quotation}/store-new-quotationrows', [Products::getController('quotation', 'addQuotationrow'), 'storeQuotationrow'])->name('quotations.storeQuotationrow');

		Route::get('', [Products::getController('quotation', 'index'), 'index'])->name('quotations.index');
		Route::get('create', [Products::getController('quotation', 'create'), 'create'])->name('quotations.create');
		Route::post('', [Products::getController('quotation', 'store'), 'store'])->name('quotations.store');
		Route::get('{quotation}', [Products::getController('quotation', 'show'), 'show'])->name('quotations.show');
		Route::get('{quotation}/edit', [Products::getController('quotation', 'edit'), 'edit'])->name('quotations.edit');
		Route::put('{quotation}', [Products::getController('quotation', 'edit'), 'update'])->name('quotations.update');

		Route::delete('{quotation}/delete', [Products::getController('quotation', 'destroy'), 'destroy'])->name('quotations.destroy');
	});

	Route::group(['prefix' => 'suppliers'], function ()
	{
		Route::get('by-category/{category}', [Products::getController('supplier', 'byCategory'), 'byCategory'])->name('suppliers.byCategory');

		Route::get('', [Products::getController('supplier', 'index'), 'index'])->name('suppliers.index');
		Route::get('{supplier}', [Products::getController('supplier', 'show'), 'show'])->name('suppliers.show');

		//SellableSupplierCreateStoreBySupplierController
		Route::get('{supplier}/create-sellable-supplier', [Products::getController('sellableSupplier', 'createBySupplier'), 'createBySupplier'])->name('suppliers.createSellableSupplier');
		Route::post('{supplier}/store-sellable-supplier', [Products::getController('sellableSupplier', 'createBySupplier'), 'storeBySupplier'])->name('suppliers.storeSellableSupplier');

		//	Route::get('create', [Products::getController('supplier', 'create'), 'create'])->name('suppliers.create');
		//	Route::post('', [Products::getController('supplier', 'create'), 'store'])->name('suppliers.store');

		Route::get('{supplier}/edit', [Products::getController('supplier', 'edit'), 'edit'])->name('suppliers.edit');
		Route::put('{supplier}', [Products::getController('supplier', 'edit'), 'update'])->name('suppliers.update');

		Route::delete('{supplier}/delete', [Products::getController('supplier', 'destroy'), 'destroy'])->name('suppliers.destroy');
	});

	Route::group(['prefix' => 'sellables'], function ()
	{
		//SellableSupplierCreateStoreBySellableController
		Route::get('{sellable}/create-sellable-supplier', [Products::getController('sellableSupplier', 'createBySellable'), 'createBySellable'])->name('sellables.createSellableSupplier');
		//SellableSupplierCreateStoreBySellableController
		Route::post('{sellable}/store-sellable-supplier', [Products::getController('sellableSupplier', 'createBySellable'), 'storeBySellable'])->name('sellables.storeSellableSupplier');

		//SellableCreateStoreController
		Route::get('create', [Products::getController('sellable', 'create'), 'create'])->name('sellables.create');
		Route::post('', [Products::getController('sellable', 'create'), 'store'])->name('sellables.store');

		Route::get('', [Products::getController('sellable', 'index'), 'index'])->name('sellables.index');
		Route::get('{sellable}', [Products::getController('sellable', 'show'), 'show'])->name('sellables.show');

		Route::get('{sellable}/edit', [Products::getController('sellable', 'edit'), 'edit'])->name('sellables.edit');
		Route::put('{sellable}', [Products::getController('sellable', 'edit'), 'update'])->name('sellables.update');

		Route::delete('{sellable}/delete', [Products::getController('sellable', 'destroy'), 'destroy'])->name('sellables.destroy');
	});

	Route::group(['prefix' => 'sellable-suppliers'], function ()
	{
		Route::get('', [Products::getController('sellableSupplier', 'index'), 'index'])->name('sellableSuppliers.index');
		Route::get('{sellableSupplier}', [Products::getController('sellableSupplier', 'show'), 'show'])->name('sellableSuppliers.show');

		//SellableSupplierCreateStoreController
		Route::get('create', [Products::getController('sellableSupplier', 'create'), 'create'])->name('sellableSuppliers.create');
		Route::post('', [Products::getController('sellableSupplier', 'create'), 'store'])->name('sellableSuppliers.store');

		Route::get('{sellableSupplier}/edit', [Products::getController('sellableSupplier', 'edit'), 'edit'])->name('sellableSuppliers.edit');
		Route::put('{sellableSupplier}', [Products::getController('sellableSupplier', 'edit'), 'update'])->name('sellableSuppliers.update');

		Route::delete('{sellableSupplier}/delete', [Products::getController('sellableSupplier', 'destroy'), 'destroy'])->name('sellableSuppliers.destroy');
	});

	Route::group(['prefix' => 'products-clients'], function ()
	{
		Route::get('current', [Products::getController('client', 'index'), 'index'])->name('clients.index');
	});

	Route::group(['prefix' => 'products'], function ()
	{
		Route::get('current', [Products::getController('product', 'current'), 'index'])->name('products.current');

		Route::get('', [Products::getController('product', 'index'), 'index'])->name('products.index');
		Route::get('{product}', [Products::getController('product', 'show'), 'show'])->name('products.show');
		Route::get('{product}/edit', [Products::getController('product', 'edit'), 'edit'])->name('products.edit');
		Route::put('{product}', [Products::getController('product', 'edit'), 'update'])->name('products.update');

		Route::delete('{product}/delete', [Products::getController('product', 'destroy'), 'destroy'])->name('products.destroy');

		Route::get('{product}/reorder-phases', [Products::getController('phase', 'reorder'), 'reorder'])->name('phases.reorder');

		Route::post('{product}/reorder-phases', [Products::getController('phase', 'reorder'), 'storeReorder'])->name('phases.storeReorder');
	});

	Route::group(['prefix' => 'packing'], function ()
	{
		Route::delete('delete-media/{packing}/{media}', [Products::getController('packing', 'deleteMedia'), 'deleteMedia'])->name('packings.deleteMedia');
		// Route::get('', [Products::getController('product', 'index'), 'index'])->name('products.index');
		// Route::get('{product}', [Products::getController('product', 'show'), 'show'])->name('products.show');
		Route::get('{packing}/edit', [Products::getController('packing', 'edit'), 'edit'])->name('packings.edit');
		Route::put('{packing}', [Products::getController('packing', 'edit'), 'update'])->name('packings.update');

		// Route::delete('{product}/delete', [Products::getController('product', 'destroy'), 'destroy'])->name('products.destroy');

		// Route::get('{product}/reorder-phases', [Products::getController('phase', 'reorder'), 'reorder'])->name('phases.reorder');

		// Route::post('{product}/reorder-phases', [Products::getController('phase', 'reorder'), 'storeReorder'])->name('phases.storeReorder');
	});

	Route::group(['prefix' => 'products-relations'], function ()
	{
		Route::get('by-product/{product}', [Products::getController('productRelation', 'index'), 'index'])->name('productRelations.index');
		Route::get('{productRelation}', [Products::getController('productRelation', 'show'), 'show'])->name('productRelations.show');

		Route::get('{productRelation}/edit', [Products::getController('productRelation', 'edit'), 'edit'])->name('productRelations.edit');
		Route::put('{productRelation}', [Products::getController('productRelation', 'edit'), 'update'])->name('productRelations.update');
	});

	Route::group(['prefix' => 'phases'], function ()
	{
		Route::get('{phase}', [Products::getController('phase', 'show'), 'show'])->name('phases.show');
		Route::get('{phase}/edit', [Products::getController('phase', 'edit'), 'edit'])->name('phases.edit');
		Route::put('{phase}', [Products::getController('phase', 'update'), 'update'])->name('phases.update');
	});

	Route::group(['prefix' => 'accessories'], function ()
	{
		Route::delete('delete-media/{accessory}/{media}', [Products::getController('accessory', 'media'), 'deleteMedia'])->name('accessories.deleteMedia');

		Route::get('', [Products::getController('accessory', 'crud'), 'index'])->name('accessories.index');
		Route::get('create', [Products::getController('accessory', 'crud'), 'create'])->name('accessories.create');
		Route::post('', [Products::getController('accessory', 'crud'), 'store'])->name('accessories.store');

		Route::get('{accessory}', [Products::getController('accessory', 'crud'), 'show'])->name('accessories.show');

		Route::get('{accessory}/edit', [Products::getController('accessory', 'crud'), 'edit'])->name('accessories.edit');
		Route::put('{accessory}', [Products::getController('accessory', 'crud'), 'update'])->name('accessories.update');

		Route::delete('{accessory}', [Products::getController('accessory', 'crud'), 'destroy'])->name('accessories.destroy');
	});

	Route::group(['prefix' => 'accessory-products'], function ()
	{
		Route::get('by-product/{product}', [Products::getController('accessoryProduct', 'index'), 'index'])->name('accessoryProducts.index');
		Route::get('{accessoryProduct}', [Products::getController('accessoryProduct', 'show'), 'show'])->name('accessoryProducts.show');

		Route::get('{accessoryProduct}/edit', [Products::getController('accessoryProduct', 'edit'), 'edit'])->name('accessoryProducts.edit');
		Route::put('{accessoryProduct}', [Products::getController('accessoryProduct', 'edit'), 'update'])->name('accessoryProducts.update');
	});

	Route::group(['prefix' => 'orders'], function ()
	{
		Route::post('{order}/add-row/type/{type}', [Products::getController('order', 'addOrderrow'), 'addOrderrow'])->name('orders.addOrderrow');

		//DestinationCreateStoreController
		Route::get('{order}/create-destination', [config('clients.models.destination.controllers.create'), 'createFromOrder'])->name('orders.createDestination');

		Route::get('active-by-client/{client}', [Products::getController('order', 'activeByClient'), 'index'])->name('orders.active.byClient');

		Route::get('', [Products::getController('order', 'index'), 'index'])->name('orders.index');
		Route::get('all-orders', [Products::getController('order', 'all'), 'index'])->name('orders.all');
		Route::get('current', [Products::getController('order', 'active'), 'index'])->name('orders.current');
		Route::get('active', [Products::getController('order', 'active'), 'index'])->name('orders.active');
		Route::get('create', [Products::getController('order', 'create'), 'create'])->name('orders.create');
		Route::get('{order}', [Products::getController('order', 'show'), 'show'])->name('orders.show');
		Route::get('{order}/edit', [Products::getController('order', 'edit'), 'edit'])->name('orders.edit');
		Route::put('{order}', [Products::getController('order', 'edit'), 'update'])->name('orders.update');

		Route::delete('{order}/delete', [Products::getController('order', 'destroy'), 'destroy'])->name('orders.destroy');
	});

	Route::group(['prefix' => 'orderrows'], function ()
	{
		Route::post('reorder', [Products::getController('orderrow', 'reorder'), 'storeMassReorder'])->name('orderrows.storeMassReorder');

		Route::get('{orderrow}/assign-sellable-supplier/{sellableSupplier}', [
			Products::getController('orderrow', 'assignSellableSupplier'),
			'associateSellableSupplier'
		])->name('orderrows.associateSellableSupplier');

		//OrderrowAssignSellableSupplierController
		Route::get('{orderrow}/assign-sellable-supplier', [Products::getController('orderrow', 'assignSellableSupplier'), 'assignSellableSupplier'])->name('orderrows.assignSellableSupplier');

		Route::get('', [Products::getController('orderrow', 'index'), 'index'])->name('orderrows.index');
		Route::get('create', [Products::getController('orderrow', 'create'), 'create'])->name('orderrows.create');
		Route::post('', [Products::getController('orderrow', 'store'), 'store'])->name('orderrows.store');
		Route::get('{orderrow}', [Products::getController('orderrow', 'show'), 'show'])->name('orderrows.show');
		Route::get('{orderrow}/edit', [Products::getController('orderrow', 'edit'), 'edit'])->name('orderrows.edit');
		Route::put('{orderrow}', [Products::getController('orderrow', 'edit'), 'update'])->name('orderrows.update');

		Route::delete('{orderrow}/delete', [Products::getController('orderrow', 'destroy'), 'destroy'])->name('orderrows.destroy');
	});

	Route::group(['prefix' => 'quotationrows'], function ()
	{
		Route::post('reorder', [Products::getController('quotationrow', 'reorder'), 'storeMassReorder'])->name('quotationrows.storeMassReorder');
		Route::get('{quotationrow}/assign-sellable-supplier/{sellableSupplier}', [
			Products::getController('quotationrow', 'assignSellableSupplier'),
			'associateSellableSupplier'
		])->name('quotationrows.associateSellableSupplier');

		//QuotationrowAssignSellableSupplierController
		Route::get('{quotationrow}/assign-sellable-supplier', [Products::getController('quotationrow', 'assignSellableSupplier'), 'assignSellableSupplier'])->name('quotationrows.assignSellableSupplier');

		Route::get('', [Products::getController('quotationrow', 'index'), 'index'])->name('quotationrows.index');
		Route::get('create', [Products::getController('quotationrow', 'create'), 'create'])->name('quotationrows.create');
		Route::post('', [Products::getController('quotationrow', 'store'), 'store'])->name('quotationrows.store');
		Route::get('{quotationrow}', [Products::getController('quotationrow', 'show'), 'show'])->name('quotationrows.show');
		Route::get('{quotationrow}/edit', [Products::getController('quotationrow', 'edit'), 'edit'])->name('quotationrows.edit');
		Route::put('{quotationrow}', [Products::getController('quotationrow', 'edit'), 'update'])->name('quotationrows.update');

		//QuotationrowDestroyController
		Route::delete('{quotationrow}/delete', [Products::getController('quotationrow', 'destroy'), 'destroy'])->name('quotationrows.destroy');
	});

	Route::group(['prefix' => 'order-products'], function ()
	{
		Route::group([
			'prefix' => '{orderProduct}',
			'as' => 'orderProducts.'
		], function ()
		{
			Route::get('notes-popup', [OrderProductNotesController::class, 'getPopupByModel'])->name('getNotesPopup');
		});

		Route::get('order-products/{orderProduct}/restore', [Products::getController('orderProduct', 'restore'), 'restore'])->name('orderProducts.restore');

		Route::group(['prefix' => 'by-workstation/{workstation}'], function ()
		{
			Route::get('', [Products::getController('orderProduct', 'toElaboratebyWorkstation'), 'index'])->name('orderProducts.byWorkstation.toElaborate');
			Route::get('elaborated', [Products::getController('orderProduct', 'elaboratedByWorkstation'), 'index'])->name('orderProducts.byWorkstation.elaborated');
		});

		Route::get('{orderProduct}', [Products::getController('orderProduct', 'show'), 'show'])->name('orderProducts.show');
		Route::get('{orderProduct}/edit', [Products::getController('orderProduct', 'edit'), 'edit'])->name('orderProducts.edit');
		Route::put('{orderProduct}', [Products::getController('orderProduct', 'edit'), 'update'])->name('orderProducts.update');
	});

	Route::group(['prefix' => 'order-product-phases'], function ()
	{
		Route::group(['prefix' => 'assignees'], function ()
		{
			Route::get('set-assignee', [Products::getController('assigneeTarget', 'orderProductPhasesAssignController'), 'index'])->name('orderProductPhases.assignees.index');

			Route::get('workstation-fetcher/{workstation}', [
				Products::getController('assigneeTarget', 'orderProductPhasesAssignController'),
				'workstationFetcher'
			])->name('orderProductPhases.assignees.workstationFetcher');

			Route::get('get-assignees-popup', [Products::getController('assigneeTarget', 'orderProductPhasesAssignController'), 'assigneesPopup'])->name('orderProductPhases.assignees.popup');

			Route::post('set-assignees', [Products::getController('assigneeTarget', 'orderProductPhasesAssignController'), 'setAssignees'])->name('orderProductPhases.assignees.setAssignees');

			Route::post('remove-assignees', [Products::getController('assigneeTarget', 'orderProductPhasesAssignController'), 'removeAssignees'])->name('orderProductPhases.assignees.removeAssignees');
		});

		Route::group(['prefix' => 'by-workstation/{workstation}'], function ()
		{
			//ToElaborateByWorkstationOrderProductPhaseIndexController
			Route::get('to-elaborate', [Products::getController('orderProductPhase', 'toElaboratebyWorkstation'), 'index'])->name('orderProductPhases.byWorkstation.toElaborate');

			Route::get('elaborated', [Products::getController('orderProductPhase', 'elaboratedByWorkstation'), 'index'])->name('orderProductPhases.byWorkstation.elaborated');
		});

		Route::group(['prefix' => '{orderProductPhase}'], function ()
		{
			Route::delete('destroy', [Products::getController('orderProductPhase', 'destroy'), 'destroy'])->name('orderProductPhases.destroy');
			Route::get('complete', [Products::getController('orderProductPhase', 'complete'), 'complete'])->name('orderProductPhases.complete');

			Route::get('reopen', [Products::getController('orderProductPhase', 'reopen'), 'reopen'])->name('orderProductPhases.reopen');

			Route::get('reopen-initialization', [Products::getController('orderProductPhase', 'reopen'), 'reopenMachineInitialization'])->name('orderProductPhases.reopenMachineInitialization');
		});

		Route::get('by-order-product/{orderProduct}', [Products::getController('orderProductPhase', 'byOrderProductIndex'), 'index'])->name('orderProductPhases.byOrderProduct');

		Route::get('{orderProductPhase}', [Products::getController('orderProductPhase', 'show'), 'show'])->name('orderProductPhases.show');

		Route::get('{orderProductPhase}/edit', [Products::getController('orderProductPhase', 'edit'), 'edit'])->name('orderProductPhases.edit');
		Route::put('{orderProductPhase}', [Products::getController('orderProductPhase', 'edit'), 'update'])->name('orderProductPhases.update');
	});
});