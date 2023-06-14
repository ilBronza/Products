<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('products.models.accessory.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('slug');
            $table->string('temp_position')->nullable();

            $table->unsignedInteger('temp_quantity_in_stock')->nullable();
            $table->unsignedInteger('quantity_neeeded_in_stock')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('products.models.accessoryProduct.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on(
                config('products.models.product.table')
            );

            $table->uuid('phase_id')->nullable();
            $table->foreign('phase_id')->references('id')->on(
                config('products.models.phase.table')
            );

            $table->uuid('accessory_id')->nullable();
            $table->foreign('accessory_id')->references('id')->on(
                config('products.models.accessory.table')
            );

            $table->unsignedInteger('quantity_coefficient')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('products.models.accessoryProduct.table'));
        Schema::dropIfExists(config('products.models.accessory.table'));
    }
}
