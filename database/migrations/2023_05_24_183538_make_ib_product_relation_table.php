<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeIbProductRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('products.models.productRelation.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on(
                config('products.models.product.table')
            );

            $table->uuid('child_id')->nullable();
            $table->foreign('child_id')->references('id')->on(
                config('products.models.product.table')
            );

            $table->string('main_code')->nullable();
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
        Schema::dropIfExists(config('products.models.productRelation.table'));
    }
}
