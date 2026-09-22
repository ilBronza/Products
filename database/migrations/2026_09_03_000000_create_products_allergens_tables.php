<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		Schema::create(config('products.models.allergen.table'), function (Blueprint $table)
		{
			$table->uuid('id')->primary();
			$table->string('name', 64);
			$table->string('slug', 64)->nullable();
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create(config('products.models.allergenable.table'), function (Blueprint $table)
		{
			$table->uuid('id')->primary();

			$table->uuid('allergen_id');
			$table->foreign('allergen_id')
				->references('id')
				->on(config('products.models.allergen.table'));

			$table->uuidMorphs('allergenable', 'products_allergenables_allergenable_index');
			$table->unique(
				['allergen_id', 'allergenable_type', 'allergenable_id'],
				'products_allergenables_unique'
			);

			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down() : void
	{
		Schema::dropIfExists(config('products.models.allergenable.table'));
		Schema::dropIfExists(config('products.models.allergen.table'));
	}
};
