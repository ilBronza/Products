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
        Schema::table(config('products.models.order.table'), function (Blueprint $table) {
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->foreign('quotation_id')->references('id')->on(config('products.models.quotation.table'));

        });

        Schema::create(config('products.models.orderrow.table'), function (Blueprint $table) {
            $table->id();

            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on(config('products.models.order.table'));

            $table->uuid('sellable_supplier_id', 'sellable_supplier_id')->nullable();
            $table->foreign('sellable_supplier_id')->references('id')->on(config('products.models.sellableSupplier.table'));

            $table->unsignedBigInteger('price_id')->nullable();
            $table->foreign('price_id')->references('id')->on(config('prices.models.price.table'));

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->unsignedInteger('sorting_index')->nullable();

            $table->decimal('quantity', 10, 2)->nullable();

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->text('parameters')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on(config('products.models.orderrow.table'));

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('products.models.orderrow.table'));

        Schema::table(config('products.models.order.table'), function (Blueprint $table) {
                $table->dropForeign(['quotation_id']);
                $table->dropColumn('quotation_id');
        });
    }
};
