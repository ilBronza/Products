<?php

namespace IlBronza\Products;

use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\OrderProduct;
use IlBronza\Products\Models\OrderProductPhase;
use IlBronza\Products\Models\Packing;
use IlBronza\Products\Models\Phase;
use IlBronza\Products\Models\Product\Product;
use IlBronza\Products\Models\Quotations\Quotation;
use IlBronza\Products\Models\Quotations\Quotationrow;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class ProductsServiceProvider extends ServiceProvider
{
	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot() : void
	{
		Relation::morphMap([
			'Quotationrow' => Quotationrow::getProjectClassName(),
			'Quotation' => Quotation::getProjectClassName(),
			'Supplier' => Supplier::getProjectClassName(),
			'SellableSupplier' => SellableSupplier::getProjectClassName(),
			'Product' => Product::getProjectClassName(),
			'Phase' => Phase::getProjectClassName(),
			'Packing' => Packing::getProjectClassName(),
			'Order' => Order::getProjectClassName(),
			'OrderProduct' => OrderProduct::getProjectClassName(),
			'OrderProductPhase' => OrderProductPhase::getProjectClassName(),
		]);

		$this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'products');
		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'products');
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
		$this->loadRoutesFrom(__DIR__ . '/../routes/products.php');

		// Publishing is only necessary when using the CLI.
		if ($this->app->runningInConsole())
		{
			$this->bootForConsole();
		}
	}

	/**
	 * Register any package services.
	 *
	 * @return void
	 */
	public function register() : void
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/products.php', 'products');

		// Register the service the package provides.
		$this->app->singleton('products', function ($app)
		{
			return new Products;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['products'];
	}

	/**
	 * Console-specific booting.
	 *
	 * @return void
	 */
	protected function bootForConsole() : void
	{
		// Publishing the configuration file.
		$this->publishes([
			__DIR__ . '/../config/products.php' => config_path('products.php'),
		], 'products.config');

		// Publishing the views.
		/*$this->publishes([
			__DIR__.'/../resources/views' => base_path('resources/views/vendor/ilbronza'),
		], 'products.views');*/

		// Publishing assets.
		/*$this->publishes([
			__DIR__.'/../resources/assets' => public_path('vendor/ilbronza'),
		], 'products.views');*/

		// Publishing the translation files.
		// $this->publishes([
		//     __DIR__.'/../resources/lang' => resource_path('lang/vendor/ilbronza'),
		// ], 'products.lang');

		// Registering package commands.
		// $this->commands([]);
	}
}
