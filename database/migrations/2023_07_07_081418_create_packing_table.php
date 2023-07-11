<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('products.models.packing.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('packable_type')->nullable();
            $table->string('packable_id', 36)->nullable();

            $table->unsignedBigInteger('pallet_id')->nullable();
            $table->foreign('pallet_id')->references('id')->on('pallets');

            $table->decimal('package_width')->nullable();
            $table->decimal('package_height')->nullable();
            $table->decimal('package_lenght')->nullable();
            $table->decimal('package_weight')->nullable();
            $table->decimal('package_volume_mq')->nullable();

            $table->decimal('quantity_per_package')->nullable();
            $table->unsignedInteger('package_per_layer')->nullable();
            $table->unsignedInteger('layers_per_packing')->nullable();
            $table->decimal('quantity_per_packing')->nullable();

            $table->decimal('packing_width')->nullable();
            $table->decimal('packing_height')->nullable();
            $table->decimal('packing_lenght')->nullable();
            $table->decimal('packing_weight')->nullable();
            $table->decimal('packing_volume_mq')->nullable();

            $table->timestamp('imported_at')->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table(config('products.models.product.table'), function (Blueprint $table)
        {
            $table->uuid('packing_id')->after('client_id')->nullable();
            $table->foreign('packing_id')->references('id')->on(config('products.models.packing.table'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('products.models.packing.table'));
    }
}
