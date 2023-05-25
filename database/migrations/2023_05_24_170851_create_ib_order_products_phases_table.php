<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIbOrderProductsPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('products.models.orderProductPhase.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('order_product_id')->nullable();
            $table->foreign('order_product_id')->references('id')->on(
                config('products.models.orderProduct.table')
            );

            $table->uuid('phase_id')->nullable();
            $table->foreign('phase_id')->references('id')->on(
                config('products.models.phase.table')
            );

            $table->unsignedInteger('sorting_index')->nullable();

            $table->unsignedInteger('quantity_required')->nullable();
            $table->unsignedInteger('quantity_done')->nullable();

            $table->string('workstation_overridden_id', 36)->nullable();
            $table->foreign('workstation_overridden_id')->references('alias')->on(
                config('products.models.workstation.table')
            );

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
        Schema::dropIfExists(config('products.models.orderProductPhase.table'));
    }
}
