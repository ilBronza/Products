<?php

namespace IlBronza\Products\Tests\Feature;

require_once __DIR__ . '/../../../Crud/tests/Fixtures/App/Http/Controllers/Controller.php';
require_once __DIR__ . '/../../../Crud/tests/Fixtures/App/Models/User.php';

use IlBronza\AccountManager\Models\User;
use IlBronza\Buttons\ButtonsServiceProvider;
use IlBronza\Form\FormServiceProvider;
use IlBronza\FormField\FormFieldServiceProvider;
use IlBronza\Menu\MenuServiceProvider;
use IlBronza\Products\Http\Controllers\Finishings\FinishingEditUpdateController;
use IlBronza\Products\Models\Finishing;
use IlBronza\Products\Tests\TestCase;
use IlBronza\UikitTemplate\UikitTemplateServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mockery;

class FinishingsTest extends TestCase
{
	protected function getPackageProviders($app) : array
	{
		return array_merge(parent::getPackageProviders($app), [
			ButtonsServiceProvider::class,
			FormFieldServiceProvider::class,
			FormServiceProvider::class,
			MenuServiceProvider::class,
			UikitTemplateServiceProvider::class,
		]);
	}

	protected function defineEnvironment($app) : void
	{
		parent::defineEnvironment($app);
		$app['config']->set('menu.serviceProviders', []);
	}

	protected function setUp() : void
	{
		parent::setUp();

		view()->addNamespace('crud', __DIR__ . '/../../../Crud/resources/views');
		Schema::create(config('products.models.finishing.table'), function ($table)
		{
			$table->uuid('id')->primary();
			$table->string('name');
			$table->string('short_description')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});

		$user = Mockery::mock(User::class)->makePartial();
		$user->id = 1;
		$user->shouldReceive('isSuperadmin')->andReturn(true);
		$this->actingAs($user);
	}

	public function test_show_route_builds_the_detail_with_the_saved_fields() : void
	{
		$finishing = $this->makeFinishing();
		$route = Route::getRoutes()->getByName(config('products.routePrefix') . 'finishings.show');
		$request = Request::create($finishing->getShowUrl());
		$route->bind($request);
		$request->setRouteResolver(fn () => $route);
		$this->app->instance('request', $request);
		$controller = $route->getController();
		$view = $controller->callAction($route->getActionMethod(), [$finishing->getKey()]);

		$this->assertSame('crud::uikit.show', $view->name());
		$this->assertSame($finishing->getKey(), $view->getData()['modelInstance']->getKey());
		$this->assertSame('Lucida', $controller->getModelFormHelper()->getForm()->getFieldByName('name')->getValue());
		$this->assertSame('Finitura lucida', $controller->getModelFormHelper()->getForm()->getFieldByName('short_description')->getValue());
	}

	public function test_edit_route_builds_a_populated_form_for_the_update_route() : void
	{
		$finishing = $this->makeFinishing();
		$route = Route::getRoutes()->getByName(config('products.routePrefix') . 'finishings.edit');
		$request = Request::create($finishing->getEditUrl());
		$route->bind($request);
		$request->setRouteResolver(fn () => $route);
		$this->app->instance('request', $request);
		$controller = $route->getController();
		$view = $controller->callAction($route->getActionMethod(), [$finishing->getKey()]);
		$form = $controller->getModelFormHelper()->getForm();

		$this->assertSame('form::uikit.form', $view->name());
		$this->assertSame($finishing->getUpdateUrl(), $form->getAction());
		$this->assertSame('Lucida', $form->getFieldByName('name')->getValue());
		$this->assertSame('Finitura lucida', $form->getFieldByName('short_description')->getValue());
	}

	public function test_update_validation_accepts_finishing_fields_and_discards_unexpected_fields() : void
	{
		$controller = new FinishingEditUpdateController();
		$controller->setModel($this->makeFinishing());

		$this->assertSame([
			'name' => 'Opaca',
			'short_description' => null,
		], $controller->getValidatedUpdateParameters(Request::create('/finishings', 'PUT', [
			'name' => 'Opaca',
			'short_description' => null,
			'unexpected' => 'ignored',
		])));
	}

	public function test_update_validation_rejects_finishing_fields_longer_than_255_characters() : void
	{
		$controller = new FinishingEditUpdateController();
		$controller->setModel($this->makeFinishing());

		try
		{
			$controller->getValidatedUpdateParameters(Request::create('/finishings', 'PUT', [
				'name' => str_repeat('x', 256),
				'short_description' => str_repeat('x', 256),
			]));
			$this->fail('Expected validation to reject both fields.');
		}
		catch (ValidationException $exception)
		{
			$this->assertArrayHasKey('name', $exception->errors());
			$this->assertArrayHasKey('short_description', $exception->errors());
		}
	}

	private function makeFinishing() : Finishing
	{
		$id = (string) Str::uuid();
		DB::table(config('products.models.finishing.table'))->insert([
			'id' => $id,
			'name' => 'Lucida',
			'short_description' => 'Finitura lucida',
		]);

		return Finishing::findOrFail($id);
	}
}
