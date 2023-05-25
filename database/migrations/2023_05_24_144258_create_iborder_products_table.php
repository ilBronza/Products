<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIborderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('products.models.orderProduct.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on(
                config('products.models.order.table')
            );

            $table->uuid('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on(
                config('products.models.product.table')
            );

            $table->uuid('destination_id')->nullable();
            $table->foreign('destination_id')->references('id')->on(
                config('clients.models.destination.table')
            );

            $table->unsignedBigInteger('quantity_required')->nullable();
            $table->unsignedBigInteger('quantity_done')->nullable();

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
        Schema::dropIfExists(config('products.models.orderProduct.table'));
    }
}
