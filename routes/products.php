<?php

use IlBronza\Products\Products;

Route::group([
	'middleware' => ['web', 'auth'],
	'prefix' => 'products-management',
	'as' => config('products.routePrefix')
	],
	function()
	{

Route::group(['prefix' => 'products'], function()
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

Route::group(['prefix' => 'products-relations'], function()
{
	Route::get('by-product/{product}', [Products::getController('productRelation', 'index'), 'index'])->name('productRelations.index');
	Route::get('{productRelation}', [Products::getController('productRelation', 'show'), 'show'])->name('productRelations.show');

	Route::get('{productRelation}/edit', [Products::getController('productRelation', 'edit'), 'edit'])->name('productRelations.edit');
	Route::put('{productRelation}', [Products::getController('productRelation', 'edit'), 'update'])->name('productRelations.update');
});

Route::group(['prefix' => 'phases'], function()
{
	Route::get('{phase}', [Products::getController('phase', 'show'), 'show'])->name('phases.show');
	Route::get('{phase}/edit', [Products::getController('phase', 'edit'), 'edit'])->name('phases.edit');
});

Route::group(['prefix' => 'accessories'], function()
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

Route::group(['prefix' => 'accessory-products'], function()
{
	Route::get('by-product/{product}', [Products::getController('accessoryProduct', 'index'), 'index'])->name('accessoryProducts.index');
	Route::get('{accessoryProduct}', [Products::getController('accessoryProduct', 'show'), 'show'])->name('accessoryProducts.show');

	Route::get('{accessoryProduct}/edit', [Products::getController('accessoryProduct', 'edit'), 'edit'])->name('accessoryProducts.edit');
	Route::put('{accessoryProduct}', [Products::getController('accessoryProduct', 'edit'), 'update'])->name('accessoryProducts.update');
});

Route::group(['prefix' => 'orders'], function()
{
	Route::get('', [Products::getController('order', 'index'), 'index'])->name('orders.index');
	Route::get('{order}', [Products::getController('order', 'show'), 'show'])->name('orders.show');
	Route::get('{order}/edit', [Products::getController('order', 'edit'), 'edit'])->name('orders.edit');
	Route::put('{order}', [Products::getController('order', 'edit'), 'update'])->name('orders.update');

	Route::delete('{order}/delete', [Products::getController('order', 'destroy'), 'destroy'])->name('orders.destroy');
});

Route::group(['prefix' => 'order-products'], function()
{
	Route::get('{orderProduct}', [Products::getController('orderProduct', 'show'), 'show'])->name('orderProducts.show');
	Route::get('{orderProduct}/edit', [Products::getController('orderProduct', 'edit'), 'edit'])->name('orderProducts.edit');
	Route::put('{orderProduct}', [Products::getController('orderProduct', 'edit'), 'update'])->name('orderProducts.update');
});

Route::group(['prefix' => 'order-product-phases'], function()
{
	Route::get('{orderProductPhase}', [Products::getController('orderProductPhase', 'show'), 'show'])->name('orderProductPhases.show');
});

});