<?php

use IlBronza\Products\Products;

Route::group([
	'middleware' => ['web', 'auth'],
	'prefix' => 'products-management',
	'as' => config('products.routePrefix')
	],
	function()
	{
		Route::get('current-products', [Products::getController('product', 'current'), 'index'])->name('products.current');

		Route::get('products', [Products::getController('product', 'index'), 'index'])->name('products.index');

		Route::get('products/{product}', [Products::getController('product', 'show'), 'show'])->name('products.show');

		Route::get('products/{product}/edit', [Products::getController('product', 'edit'), 'edit'])->name('products.edit');

		// Route::resource('clients', Clients::getController('client'));

		// Route::get(
		// 	'clients/{client}/destinations/create',
		// 	[
		// 		Clients::getController('destination'),
		// 		'createFromClient'
		// 	]
		// )->name('clients.destinations.create');

		// Route::get(
		// 	'clients/{client}/referents/create',
		// 	[
		// 		Clients::getController('referent'),
		// 		'createFromClient'
		// 	]
		// )->name('clients.referents.create');

		// Route::get(
		// 	'clients/{client}/clienthashes/create',
		// 	[
		// 		Clients::getController('clienthash'),
		// 		'createFromClient'
		// 	]
		// )->name('clients.clienthashes.create');


		// Route::resource('clienthashes', Clients::getController('clienthash'));

		// Route::resource('destinations', Clients::getController('destination'));
		// Route::resource('referents', Clients::getController('referent'));
		// Route::resource('destinationtypes', Clients::getController('destinationtype'));
		// Route::resource('referenttypes', Clients::getController('referenttype'));
	});