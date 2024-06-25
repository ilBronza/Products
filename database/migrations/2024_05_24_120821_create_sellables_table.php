<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(! config('products.sellables.enabled', false))
            return ;

        Schema::create(config('products.models.supplier.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuidMorphs('target', 'supplier_foreign_key');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('products.models.sellable.table'), function (Blueprint $table) {
            $table->id('id');

            $table->string('name');
            $table->string('slug')->nullable();

            $table->nullableUuidMorphs('target');

            $table->enum('type', ['phisycal', 'service', 'virtual', 'rent'])->nullable();

            $table->uuid('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on(config('category.models.category.table'));

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('products.models.sellableSupplier.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('sellable_id')->nullable();
            $table->foreign('sellable_id')->references('id')->on(config('products.models.sellable.table'));

            $table->uuid('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on(config('products.models.supplier.table'));

            $table->unsignedBigInteger('price_id')->nullable();
            // $table->foreign('price_id')->references('id')->on(config('prices.models.price.table'));

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('products.models.sellableRelation.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on(config('products.models.sellable.table'));

            $table->unsignedBigInteger('child_id')->nullable();
            $table->foreign('child_id')->references('id')->on(config('products.models.sellable.table'));

            $table->string('main_code')->nullable();
            $table->boolean('included')->nullable();
            $table->unsignedInteger('quantity_coefficient')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(! config('products.sellables.enabled', false))
            return ;

        Schema::dropIfExists(config('products.models.sellableRelation.table'));
        Schema::dropIfExists(config('products.models.sellableSupplier.table'));
        Schema::dropIfExists(config('products.models.sellable.table'));
        Schema::dropIfExists(config('products.models.supplier.table'));
    }
};
