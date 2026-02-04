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
        Schema::create('products__extrafields__quotations_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quotation_id')->nullable();
            $table->foreign('quotation_id')->references('id')->on(config('products.models.quotation.table'));

            $table->uuid('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on(config('products.models.order.table'));

            $table->softDeletes();
            $table->timestamps();
        });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products__extrafields__quotations_orders');
    }
};
