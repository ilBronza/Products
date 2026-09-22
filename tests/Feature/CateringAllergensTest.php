<?php

namespace IlBronza\Products\Tests\Feature;

use IlBronza\CRUD\Helpers\ModelManagers\CrudModelAssociatorHelper;
use IlBronza\CRUD\Models\BasePivotModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use IlBronza\Products\Http\Parameters\Datatables\Catering\CateringSellableSupplierPickFieldsGroupParametersFile;
use IlBronza\Products\Models\Catering\Allergen;
use IlBronza\Products\Models\Catering\InteractsWithCateringAllergensTrait;
use IlBronza\Products\Models\Catering\Product;
use IlBronza\Products\Models\Sellables\Sellable;
use IlBronza\Products\Models\Sellables\SellableSupplier;
use IlBronza\Products\Tests\TestCase;

class CateringAllergensTest extends TestCase
{
	private string $testIconsFolder;

	protected function setUp() : void
	{
		parent::setUp();

		$this->testIconsFolder = 'test-' . Str::uuid();

		Schema::create(config('products.models.product.table'), function ($table)
		{
			$table->uuid('id')->primary();
			$table->string('name');
			$table->string('slug')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});

		$allergensMigration = require __DIR__ . '/../../database/migrations/2026_09_03_000000_create_products_allergens_tables.php';
		$allergensMigration->up();
	}

	protected function tearDown() : void
	{
		File::deleteDirectory($this->getTestIconsPath());

		parent::tearDown();
	}

	public function test_product_sync_persists_uuid_pivot_rows_and_reloads_allergens() : void
	{
		$product = new Product();
		$product->id = (string) Str::uuid();
		$product->name = 'Menu degustazione';
		$product->slug = 'menu-degustazione';
		$product->exists = true;

		DB::table(config('products.models.product.table'))->insert([
			'id' => $product->id,
			'name' => $product->name,
			'slug' => $product->slug,
			'created_at' => now(),
			'updated_at' => now(),
		]);

		$gluten = $this->makeAllergen('Glutine', true);
		$milk = $this->makeAllergen('Latte', true);

		$this->assertTrue(is_a($product->allergens()->getPivotClass(), BasePivotModel::class, true));

		$product->allergens()->sync([$gluten->getKey(), $milk->getKey()]);

		$this->assertDatabaseCount(config('products.models.allergenable.table'), 2);
		$this->assertDatabaseMissing(config('products.models.allergenable.table'), ['id' => null]);

		$associator = new CrudModelAssociatorHelper();
		$associator->syncSoftDeletingPivot($product->allergens(), [$milk->getKey()]);

		$this->assertNotNull(
			DB::table(config('products.models.allergenable.table'))
				->where('allergen_id', $gluten->getKey())
				->value('deleted_at')
		);

		$associator->attachSoftDeletingPivot($product->allergens(), [$gluten->getKey()]);

		$this->assertDatabaseCount(config('products.models.allergenable.table'), 2);
		$this->assertDatabaseHas(config('products.models.allergenable.table'), [
			'allergen_id' => $gluten->getKey(),
			'deleted_at' => null,
		]);

		$this->assertSame(
			collect([$gluten->getKey(), $milk->getKey()])->sort()->values()->all(),
			$product->fresh()->getAllergens()->pluck('id')->sort()->values()->all()
		);
	}

	public function test_product_and_container_deduplicate_allergens_and_pdf_renders_only_when_present() : void
	{
		$gluten = $this->makeAllergen('Glutine');
		$milk = $this->makeAllergen('Latte');

		$starter = new Product();
		$starter->setRelation('allergens', collect([$gluten]));
		$starter->setRelation('products', collect());

		$main = new Product();
		$main->id = (string) Str::uuid();
		$main->updated_at = now();
		$main->setRelation('allergens', collect([$gluten, $milk]));
		$main->setRelation('products', collect([$starter]));

		$this->assertSame(
			['Glutine', 'Latte'],
			$main->getAllergensList()->pluck('name')->all()
		);

		$this->assertSame('Glutine - Latte', $main->allergens_list_string);

		$container = $this->makeContainer(collect([$main, $starter]));

		$this->assertSame(
			['Glutine', 'Latte'],
			$container->getAllergensList()->pluck('name')->all()
		);

		$allergensByName = $container->getAllergensList()->keyBy('name');
		$this->assertSame(2, $allergensByName->get('Glutine')->products_in_order_count);
		$this->assertSame(1, $allergensByName->get('Latte')->products_in_order_count);

		$html = view('products::pdf._allergensList', ['container' => $container])->render();
		$this->assertStringContainsString('Allergeni in questo menu', $html);
		$this->assertStringContainsString('Glutine', $html);
		$this->assertStringContainsString('Latte', $html);

		$emptyHtml = view('products::pdf._allergensList', ['container' => $this->makeContainer(collect())])->render();
		$this->assertStringNotContainsString('Allergeni in questo menu', $emptyHtml);

		$main->setRelation('allergens', collect([$gluten]));

		$this->assertSame('Glutine - Latte', $main->allergens_list_string);
	}

	public function test_product_allergens_list_ignores_circular_descendants() : void
	{
		$gluten = $this->makeAllergen('Glutine');
		$milk = $this->makeAllergen('Latte');
		$eggs = $this->makeAllergen('Uova');

		$main = $this->makeProductWithAllergens(collect([$gluten]));
		$starter = $this->makeProductWithAllergens(collect([$milk]));
		$dessert = $this->makeProductWithAllergens(collect([$eggs]));

		$main->setRelation('descendants', collect([$starter]));
		$starter->setRelation('descendants', collect([$dessert]));
		$dessert->setRelation('descendants', collect([$main]));

		$this->assertSame(
			['Glutine', 'Latte', 'Uova'],
			$main->getAllergensList()->pluck('name')->all()
		);
	}

	public function test_allergen_renders_its_text_and_icon_from_the_requested_folder() : void
	{
		$allergen = $this->makeAllergen('Glutine');
		$icon = '<svg aria-label="Glutine"></svg>';

		File::ensureDirectoryExists($this->getTestIconsPath());
		File::put($this->getTestIconsPath() . '/glutine.svg', $icon);

		$this->assertSame('Glutine', $allergen->renderText());
		$this->assertSame($icon, $allergen->renderIcon($this->testIconsFolder));
		$this->assertNull($allergen->renderIcon('missing-folder'));
	}

	public function test_sellable_supplier_caches_allergens_from_its_sellable_target() : void
	{
		$gluten = $this->makeAllergen('Glutine');
		$milk = $this->makeAllergen('Latte');

		$firstTarget = $this->makeProductWithAllergens(collect([$gluten]));
		$secondTarget = $this->makeProductWithAllergens(collect([$milk]));

		$sellable = new Sellable();
		$sellable->setRelation('target', $firstTarget);

		$sellableSupplier = new SellableSupplier();
		$sellableSupplier->id = (string) Str::uuid();
		$sellableSupplier->updated_at = now();
		$sellableSupplier->setRelation('sellable', $sellable);

		$this->assertSame(
			'getAllergensListString',
			CateringSellableSupplierPickFieldsGroupParametersFile::getFieldsGroup($firstTarget)['fields']['mySelfAllergens']['function']
		);
		$this->assertSame('Glutine', $sellableSupplier->getAllergensListString());

		$sellable->setRelation('target', $secondTarget);

		$this->assertSame('Glutine', $sellableSupplier->getAllergensListString());
	}

	private function getTestIconsPath() : string
	{
		return dirname(__DIR__, 2) . '/resources/views/catering/allergens/icons/' . $this->testIconsFolder;
	}

	private function makeAllergen(string $name, bool $persist = false) : Allergen
	{
		$allergen = new Allergen();
		$allergen->id = (string) Str::uuid();
		$allergen->name = $name;
		$allergen->slug = Str::slug($name);

		if ($persist)
			DB::table(config('products.models.allergen.table'))->insert([
				'id' => $allergen->id,
				'name' => $allergen->name,
				'slug' => $allergen->slug,
				'created_at' => now(),
				'updated_at' => now(),
			]);

		return $allergen;
	}

	private function makeProductWithAllergens(Collection $allergens) : Product
	{
		$product = new Product();
		$product->id = (string) Str::uuid();
		$product->updated_at = now();
		$product->setRelation('allergens', $allergens);
		$product->setRelation('products', collect());

		return $product;
	}

	private function makeContainer(Collection $products) : object
	{
		$rows = $products->map(function (Product $product)
		{
			return new class($product)
			{
				public function __construct(private Product $product) {}

				public function getSellable() : object
				{
					return new class($this->product)
					{
						public function __construct(private Product $product) {}

						public function getTarget() : Product
						{
							return $this->product;
						}
					};
				}
			};
		});

		return new class($rows)
		{
			use InteractsWithCateringAllergensTrait;

			public function __construct(private Collection $rows) {}

			public function getProductRows() : Collection
			{
				return $this->rows;
			}
		};
	}
}
