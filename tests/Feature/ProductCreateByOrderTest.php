<?php

namespace IlBronza\Products\Tests\Feature;

require_once __DIR__ . '/../../../Crud/tests/Fixtures/App/Http/Controllers/Controller.php';

use IlBronza\Form\Helpers\FieldsetsProvider\FieldsetParametersFile;
use IlBronza\Products\Http\Controllers\Product\ProductCreateByOrderController;
use IlBronza\Products\Providers\RelationshipsManagers\CateringOrderRelationManager;
use IlBronza\Products\Providers\RelationshipsManagers\OrderRelationManager;
use IlBronza\Products\Models\Catering\Product;
use IlBronza\Products\Models\Order;
use IlBronza\Products\Models\Orders\Orderrow;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Models\Sellables\Supplier;
use IlBronza\Products\Providers\Helpers\RowsHelpers\RowAssociatorHelper;
use Illuminate\Support\Collection;
use IlBronza\Products\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductCreateByOrderTest extends TestCase
{
	protected function getPackageProviders($app) : array
	{
		return array_merge(parent::getPackageProviders($app), [
			\IlBronza\FormField\FormFieldServiceProvider::class,
		]);
	}

	protected function setUp() : void
	{
		parent::setUp();

		config([
			'products.models.order.class' => PopupOrder::class,
			'products.models.product.class' => PopupProduct::class,
			'products.models.sellable.class' => PopupSellable::class,
			'products.models.sellableSupplier.class' => PopupSellableSupplier::class,
			'products.models.supplier.class' => PopupSupplier::class,
			'products.models.orderrow.class' => PopupOrderrow::class,
			'products.models.orderrow.fieldsGroupsFiles.popupTestRow' => 'popup-test-fields',
		]);
		PopupProduct::$canCreate = true;

		view()->addNamespace('crud', __DIR__ . '/../../../Crud/resources/views');

		foreach (['order', 'product', 'sellable', 'supplier', 'sellableSupplier', 'orderrow'] as $model)
			Schema::create(config("products.models.{$model}.table"), function ($table) use ($model)
			{
				$table->uuid('id')->primary();
				$table->string('name')->nullable();
				$table->softDeletes();
				$table->timestamps();
				if ($model === 'order')
				{
					$table->boolean('frozen')->default(false);
					$table->boolean('can_update')->default(true);
				}
				if ($model === 'sellable')
				{
					$table->uuid('target_id');
					$table->string('target_type');
					$table->string('type');
				}
				if (in_array($model, ['product', 'sellableSupplier']))
				{
					$table->decimal('single_cost', 10, 2)->nullable();
					$table->decimal('single_revenue', 10, 2)->nullable();
				}
				if ($model === 'product')
					$table->uuid('accessory_id')->nullable();
				if ($model === 'sellableSupplier')
				{
					$table->uuid('sellable_id');
					$table->uuid('supplier_id');
				}
				if ($model === 'orderrow')
				{
					$table->uuid('order_id');
					$table->uuid('sellable_supplier_id')->nullable();
					$table->uuid('parent_id')->nullable();
					$table->decimal('stored_single_cost', 10, 2)->nullable();
					$table->decimal('stored_single_revenue', 10, 2)->nullable();
					$table->uuid('sellable_id');
					$table->string('type');
					$table->integer('sorting_index');
				}
			});
	}

	public function test_store_creates_product_and_row_for_the_selected_order_and_closes_popup() : void
	{
		$order = $this->makeOrder();
		$otherOrder = $this->makeOrder();
		$request = $this->request(['name' => 'Nuovo piatto', 'iframed' => 1]);
		$response = (new PopupProductController())->storeByOrder($request, $order->getKey());

		$this->assertDatabaseCount(config('products.models.product.table'), 1);
		$this->assertDatabaseCount(config('products.models.orderrow.table'), 1);
		$product = PopupProduct::firstOrFail();
		$row = PopupOrderrow::firstOrFail();
		$this->assertSame('Nuovo piatto', $product->name);
		$this->assertSame($order->getKey(), $row->order_id);
		$this->assertSame($product->getKey(), $row->sellable->target_id);
		$this->assertSame('Product', $row->type);
		$this->assertSame(1, $row->sorting_index);
		$this->assertSame(0, $otherOrder->rows()->count());
		$this->assertSame('crud::utilities.messages.closeIframe', $response->name());
		$this->assertTrue($response->getData()['reloadAllTables']);
		$html = $response->render();
		$this->assertStringContainsString('window.parent.__reloadAllTables()', $html);
		$this->assertStringContainsString('window.parent.closeLightbox()', $html);
	}

	public function test_popup_copies_supplier_prices_just_like_standard_row_association() : void
	{
		$order = $this->makeOrder();
		(new PopupProductController())->storeByOrder($this->request([
			'name' => 'Piatto con prezzi',
			'single_cost' => '12.50',
			'single_revenue' => '29.90',
			'iframed' => 1,
		]), $order->getKey());

		$product = PopupProduct::firstOrFail();
		$sellableSupplier = PopupSellableSupplier::firstOrFail();
		$popupRow = $order->rows()->firstOrFail();
		$this->assertSame($sellableSupplier->getKey(), $popupRow->sellable_supplier_id);
		$this->assertEquals(12.50, $popupRow->stored_single_cost);
		$this->assertEquals(29.90, $popupRow->stored_single_revenue);

		$standardOrder = $this->makeOrder();
		RowAssociatorHelper::associateRowBySellableSupplier($standardOrder, $sellableSupplier->getKey());
		$standardRow = $standardOrder->rows()->firstOrFail();
		$this->assertSame(
			$standardRow->only(['sellable_id', 'sellable_supplier_id', 'stored_single_cost', 'stored_single_revenue']),
			$popupRow->only(['sellable_id', 'sellable_supplier_id', 'stored_single_cost', 'stored_single_revenue'])
		);

		$product->single_cost = 99;
		$product->single_revenue = 150;
		$product->save();
		$this->assertEquals(12.50, $popupRow->fresh()->stored_single_cost);
		$this->assertEquals(29.90, $popupRow->fresh()->stored_single_revenue);
	}

	public function test_popup_adds_dependent_items_with_their_supplier_and_prices() : void
	{
		$accessory = new PopupProduct();
		$accessory->name = 'Accessorio';
		$accessory->single_cost = 2;
		$accessory->single_revenue = 4;
		$accessory->save();
		$order = $this->makeOrder();
		(new PopupProductController())->storeByOrder($this->request([
			'name' => 'Piatto con accessorio',
			'single_cost' => 10,
			'single_revenue' => 20,
			'accessory_id' => $accessory->getKey(),
			'iframed' => 1,
		]), $order->getKey());

		$this->assertSame(2, $order->rows()->count());
		$parent = $order->rows()->whereNull('parent_id')->firstOrFail();
		$child = $order->rows()->where('parent_id', $parent->getKey())->firstOrFail();
		$this->assertEquals(10, $parent->stored_single_cost);
		$this->assertEquals(20, $parent->stored_single_revenue);
		$this->assertSame($accessory->getKey(), $child->sellable->target_id);
		$this->assertNotNull($child->sellable_supplier_id);
		$this->assertEquals(2, $child->stored_single_cost);
		$this->assertEquals(4, $child->stored_single_revenue);
	}

	public function test_validation_failure_does_not_create_a_product_or_a_row() : void
	{
		$order = $this->makeOrder();
		try
		{
			(new PopupProductController())->storeByOrder($this->request([]), $order->getKey());
			$this->fail('Expected validation to fail.');
		}
		catch (\Illuminate\Validation\ValidationException $exception)
		{
			$this->assertArrayHasKey('name', $exception->errors());
		}
		$this->assertDatabaseCount(config('products.models.product.table'), 0);
		$this->assertDatabaseCount(config('products.models.orderrow.table'), 0);
	}

	public function test_failed_row_association_rolls_back_product_and_sellable() : void
	{
		$order = $this->makeOrder();
		Schema::drop(config('products.models.orderrow.table'));
		try
		{
			(new PopupProductController())->storeByOrder($this->request(['name' => 'Da annullare']), $order->getKey());
			$this->fail('Expected row association to fail.');
		}
		catch (\Illuminate\Database\QueryException $exception)
		{
			$this->assertStringContainsString(config('products.models.orderrow.table'), $exception->getMessage());
		}
		$this->assertDatabaseCount(config('products.models.product.table'), 0);
		$this->assertDatabaseCount(config('products.models.sellable.table'), 0);
		$this->assertDatabaseCount(config('products.models.sellableSupplier.table'), 0);
	}

	public function test_frozen_orders_and_missing_permissions_are_rejected_before_saving() : void
	{
		foreach (['frozen', 'order_permission', 'product_permission'] as $reason)
		{
			$order = $this->makeOrder();
			$order->frozen = $reason === 'frozen';
			$order->can_update = $reason !== 'order_permission';
			$order->save();
			PopupProduct::$canCreate = $reason !== 'product_permission';
			try
			{
				(new PopupProductController())->storeByOrder($this->request(['name' => 'Non consentito']), $order->getKey());
				$this->fail('Expected access to be denied: ' . $reason);
			}
			catch (HttpException $exception)
			{
				$this->assertSame(403, $exception->getStatusCode());
			}
		}
		$this->assertDatabaseCount(config('products.models.product.table'), 0);
	}

	public function test_missing_order_is_rejected() : void
	{
		$this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
		(new PopupProductController())->storeByOrder($this->request(['name' => 'Piatto']), (string) Str::uuid());
	}

	public function test_form_action_keeps_order_and_iframe_context_and_non_popup_returns_to_order() : void
	{
		$order = $this->makeOrder();
		$controller = new PopupProductController();
		$this->request(['iframed' => 1]);
		$action = $controller->formActionForOrder($order->getKey());
		$this->assertStringContainsString('/orders/' . $order->getKey() . '/products?', $action);
		$this->assertStringContainsString('iframed=1', $action);

		$response = $controller->storeByOrder($this->request(['name' => 'Piatto']), $order->getKey());
		$this->assertSame($order->getEditUrl(), $response->getTargetUrl());
	}

	public function test_create_button_belongs_to_product_rows_and_opens_a_get_popup_only_for_an_editable_order() : void
	{
		$order = $this->makeOrder();
		$manager = new class($order) extends OrderRelationManager
		{
			public function __construct(Order $order) { $this->model = $order; }
		};
		$relations = $manager->getAllRelationsParameters()['show']['relations'];
		$buttons = $relations['productRows']['buttons'];
		$this->assertCount(1, $buttons);
		$this->assertTrue($buttons[0]->isIframe());
		$this->assertTrue($buttons[0]->isAjaxButton());
		$this->assertSame('GET', $buttons[0]->getData()['method']);
		$this->assertStringContainsString('/orders/' . $order->getKey() . '/create-product', $buttons[0]->getHref());
		$this->assertSame($buttons[0]->getHref(), $buttons[0]->getData()['route']);
		$this->assertNotEmpty($relations['productRows']['buttonsMethods']);
		foreach (['vehicleRows', 'operatorRows', 'accessoryRows'] as $relation)
			$this->assertArrayNotHasKey('buttons', $relations[$relation]);

		$order->frozen = true;
		$this->assertSame([], $manager->getAllRelationsParameters()['show']['relations']['productRows']['buttons']);
		$order->frozen = false;
		$order->exists = false;
		$this->assertSame([], $manager->getAllRelationsParameters()['show']['relations']['productRows']['buttons']);
		$order->exists = true;
		$order->can_update = false;
		$this->assertSame([], $manager->getAllRelationsParameters()['show']['relations']['productRows']['buttons']);
		$order->can_update = true;
		PopupProduct::$canCreate = false;
		$this->assertSame([], $manager->getAllRelationsParameters()['show']['relations']['productRows']['buttons']);
		PopupProduct::$canCreate = true;
		config(['products.models.product.class' => \IlBronza\Products\Models\Product\Product::class]);
		$this->assertSame([], $manager->getAllRelationsParameters()['show']['relations']['productRows']['buttons']);
	}

	public function test_catering_order_relation_manager_exposes_the_create_product_button() : void
	{
		$order = $this->makeOrder();
		$manager = new class($order) extends CateringOrderRelationManager
		{
			public function __construct(Order $order) { $this->model = $order; }
		};

		$buttons = $manager->getAllRelationsParameters()['edit']['relations']['productRows']['buttons'];

		$this->assertCount(1, $buttons);
		$this->assertTrue($buttons[0]->isIframe());
		$this->assertTrue($buttons[0]->isAjaxButton());
		$this->assertSame('GET', $buttons[0]->getData()['method']);
		$this->assertStringContainsString('/orders/' . $order->getKey() . '/create-product', $buttons[0]->getHref());
	}

	private function request(array $data) : Request
	{
		$request = Request::create('/popup-product', 'POST', $data);
		$this->app->instance('request', $request);
		return $request;
	}

	private function makeOrder() : PopupOrder
	{
		$order = new PopupOrder();
		$order->name = 'Commessa';
		$order->can_update = true;
		$order->save();
		return $order;
	}
}

// Fixtures keep prices in database columns; the actual row association and field-copying helpers run unchanged.
trait PopupPersistence
{
	public function getIncrementing() { return false; }
	public function getKeyType() { return 'string'; }

	public static function boot()
	{
		static::$traitInitializers[static::class] = [];
		static::bootSoftDeletes();
		static::creating(function ($model) { $model->id = (string) Str::uuid(); });
	}
}

class PopupOrder extends Order
{
	use PopupPersistence;
	public function userCanUpdate($user = null) { return (bool) $this->can_update; }
	public function rowRelationByProduct() { return $this->hasMany(PopupOrderrow::class, 'order_id'); }
	public function productRows() { return $this->rowRelationByProduct(); }
	public function vehicleRows() { return $this->rowRelationByProduct(); }
	public function operatorRows() { return $this->rowRelationByProduct(); }
	public function accessoryRows() { return $this->rowRelationByProduct(); }
	public function rows() { return $this->rowRelationByProduct(); }
	public function getEditUrl(array $data = []) { return url('/orders/' . $this->getKey() . '/edit'); }
}

class PopupProduct extends Product
{
	use PopupPersistence { boot as bootPersistence; }
	public static bool $canCreate = true;
	public static function userCanCreate($user = null) { return static::$canCreate; }
	public function getSellableTypeName(...$parameters) : string { return 'Product'; }
	public function getPossibleSuppliers() : Collection { return collect([PopupSupplier::getOwnerSupplier()]); }
	public function getAccessoriesAttribute() : Collection { return static::whereKey($this->accessory_id)->get(); }
	public function getAccessoryTypesAttribute() : Collection { return collect(); }

	public static function boot()
	{
		static::bootPersistence();
		static::saved(function ($product)
		{
			$sellable = $product->sellables()->first() ?? new PopupSellable();
			$sellable->name = $product->name;
			$sellable->type = 'Product';
			$sellable->target()->associate($product);
			$sellable->save();

			$sellableSupplier = PopupSellableSupplier::where('sellable_id', $sellable->getKey())->first() ?? new PopupSellableSupplier();
			$sellableSupplier->sellable()->associate($sellable);
			$sellableSupplier->supplier()->associate(PopupSupplier::getOwnerSupplier());
			$sellableSupplier->single_cost = $product->single_cost;
			$sellableSupplier->single_revenue = $product->single_revenue;
			$sellableSupplier->save();
		});
	}
}

class PopupSellable extends Sellable
{
	use PopupPersistence;
	public function getForeignKey() { return 'sellable_id'; }
}

class PopupSellableSupplier extends SellableSupplier { use PopupPersistence; }

class PopupSupplier extends Supplier
{
	use PopupPersistence;
	public static function getOwnerSupplier() : static
	{
		if ($supplier = static::where('name', 'Owner')->first())
			return $supplier;

		$supplier = new static();
		$supplier->name = 'Owner';
		$supplier->save();
		return $supplier;
	}
}

class PopupOrderrow extends Orderrow implements \IlBronza\Products\Models\Interfaces\CustomRowInterface
{
	use PopupPersistence;
	public function getTotalRowCostAttribute() : float { return 0; }
	public function getTotalRowRevenueAttribute() : float { return 0; }
	public static function getDesignedTargetConfigPackagePrefix() : string { return 'products'; }
	public function getFieldsGroupParametersKey() : string { return 'popupTestRow'; }
}

class PopupProductController extends ProductCreateByOrderController
{
	public function __construct() {}
	public function getModelClass() : string { return PopupProduct::class; }
	public function getStoreParametersClass() : FieldsetParametersFile
	{
		return FieldsetParametersFile::makeByParameters([
			'base' => ['fields' => [
				'name' => ['text' => 'required|string|max:255'],
				'single_cost' => ['number' => 'nullable|numeric'],
				'single_revenue' => ['number' => 'nullable|numeric'],
				'accessory_id' => ['text' => 'nullable|uuid'],
			]],
		]);
	}
	public function formActionForOrder(string $order) : string
	{
		$this->setOrder($order);
		return $this->addIframeContextToFormAction($this->getStoreModelAction());
	}
}
