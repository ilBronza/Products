<?php

namespace IlBronza\Products\Tests\Feature;

require_once __DIR__ . '/../../../Crud/tests/Fixtures/App/Http/Controllers/Controller.php';

use IlBronza\Prices\PricesServiceProvider;
use IlBronza\Products\Http\Controllers\Order\Catering\CateringOrderAddSellableSupplierIndexByTableController;
use IlBronza\Products\Models\Sellables\Accessories\AccessoryType;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CateringSellableSupplierPickTest extends TestCase
{
	protected function getPackageProviders($app) : array
	{
		return array_merge(parent::getPackageProviders($app), [PricesServiceProvider::class]);
	}

	protected function setUp() : void
	{
		parent::setUp();

		config(['products.models.accessoryType.class' => AccessoryType::class]);
		Relation::morphMap(['AccessoryType' => AccessoryType::class]);

		foreach (['accessoryType', 'sellable', 'supplier', 'sellableSupplier'] as $model)
			Schema::create(config("products.models.{$model}.table"), function ($table) use ($model)
			{
				$table->uuid('id')->primary();
				$table->string('name')->nullable();
				$table->softDeletes();
				$table->timestamps();

				if (in_array($model, ['sellable', 'supplier']))
				{
					$table->uuid('target_id')->nullable();
					$table->string('target_type')->nullable();
				}

				if ($model === 'sellable')
					$table->string('type');

				if ($model === 'sellableSupplier')
				{
					$table->uuid('sellable_id');
					$table->uuid('supplier_id');
					$table->string('sellable_class');
				}
			});

		Schema::create(config('prices.models.price.table'), function ($table)
		{
			$table->uuid('id')->primary();
			$table->uuid('priceable_id');
			$table->string('priceable_type');
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function test_accessory_picker_loads_all_active_suppliers_without_product_relations() : void
	{
		$accessoryTypeId = (string) Str::uuid();
		$supplierId = (string) Str::uuid();
		DB::table(config('products.models.accessoryType.table'))->insert([
			'id' => $accessoryTypeId,
			'name' => 'Tavolo',
		]);
		DB::table(config('products.models.supplier.table'))->insert(['id' => $supplierId]);

		$expectedIds = [];
		for ($index = 0; $index < 14; $index++)
		{
			$sellableId = (string) Str::uuid();
			$sellableSupplierId = (string) Str::uuid();
			DB::table(config('products.models.sellable.table'))->insert([
				'id' => $sellableId,
				'target_id' => $accessoryTypeId,
				'target_type' => 'AccessoryType',
				'type' => $index === 13 ? 'VehicleType' : 'AccessoryType',
				'deleted_at' => $index === 12 ? now() : null,
			]);
			DB::table(config('products.models.sellableSupplier.table'))->insert([
				'id' => $sellableSupplierId,
				'sellable_id' => $sellableId,
				'supplier_id' => $supplierId,
				'sellable_class' => 'AccessoryType',
			]);

			if ($index < 12)
				$expectedIds[] = $sellableSupplierId;
		}

		$controller = new class extends CateringOrderAddSellableSupplierIndexByTableController
		{
			public function __construct() {}
			public function getModelClass() : string { return SellableSupplier::class; }
		};
		$controller->type = 'AccessoryType';

		$elements = $controller->getIndexElements();

		$this->assertEqualsCanonicalizing($expectedIds, $elements->modelKeys());
		foreach ($elements as $element)
		{
			$this->assertTrue($element->relationLoaded('supplier'));
			$this->assertTrue($element->supplier->relationLoaded('target'));
			$this->assertTrue($element->relationLoaded('prices'));
			$this->assertTrue($element->sellable->relationLoaded('target'));
			$this->assertInstanceOf(AccessoryType::class, $element->sellable->target);
		}
	}
}
