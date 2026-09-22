<?php

namespace IlBronza\Products\Tests;

require_once __DIR__ . '/../../Crud/src/helpers.php';

use IlBronza\Products\ProductsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	protected function getPackageProviders($app) : array
	{
		return [
			ProductsServiceProvider::class,
		];
	}

	protected function defineEnvironment($app) : void
	{
		$app['config']->set('app.locale', 'it');
		$app['config']->set('activitylog.default_auth_driver', null);
		$app['config']->set('activitylog.enabled', false);
		$app['config']->set('database.default', 'sqlite');
		$app['config']->set('database.connections.sqlite.database', ':memory:');
	}
}
