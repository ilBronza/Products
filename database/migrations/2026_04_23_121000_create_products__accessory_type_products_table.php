<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('products.models.accessoryTypeProduct.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on(
                config('products.models.product.table')
            );

            $table->uuid('accessory_type_id')->nullable();
            $table->foreign('accessory_type_id')->references('id')->on(
                config('products.models.accessoryType.table')
            );

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('products.models.accessoryTypeProduct.table'));
    }
};

