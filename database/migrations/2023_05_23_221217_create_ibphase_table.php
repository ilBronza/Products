<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIbphaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products__workstations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('slug', 36)->unique();

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create(config('products.models.phase.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on(
                config('products.models.product.table')
            );

            $table->unsignedInteger('sequence')->nullable();

            $table->boolean('optional')->nullable();

            $table->decimal('coefficient_output')->nullable();
            $table->string('workstation_id', 36)->nullable();
            $table->foreign('workstation_id')->references('slug')->on('products__workstations');

            $table->string('name');
            $table->string('slug')->unique();

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
        Schema::dropIfExists(config('products.models.phase.table'));
    }
}
