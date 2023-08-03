<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsPackagematerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('products.models.material.table'), function (Blueprint $table)
        {
            $table->string('id', 36)->primary();

            $table->nullableUuidMorphs('materializable');

            $table->string('name');

            $table->string('measurement_unit_id', 16)->nullable();
            $table->foreign('measurement_unit_id')
                    ->references('id')
                    ->on(config('measurementUnits.models.measurementUnit.table'));


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
        Schema::dropIfExists(config('products.models.material.table'));
    }
}
