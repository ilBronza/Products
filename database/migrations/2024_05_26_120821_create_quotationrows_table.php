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
        if(! config('products.sellables.enabled', false))
            return ;

        Schema::create(config('products.models.quotationrow.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quotation_id')->nullable();
            $table->foreign('quotation_id')->references('id')->on(config('products.models.quotation.table'));

            $table->uuid('sellable_id')->nullable();
            $table->foreign('sellable_id')->references('id')->on(config('products.models.sellable.table'));

            $table->uuid('sellable_supplier_id', 'sellable_supplier_id')->nullable();
            $table->foreign('sellable_supplier_id')->references('id')->on(config('products.models.sellableSupplier.table'));

            $table->unsignedBigInteger('price_id')->nullable();
            // $table->foreign('price_id')->references('id')->on(config('prices.models.price.table'));

            $table->uuid('parent_id')->nullable();

            $table->unsignedInteger('sorting_index')->nullable();

            $table->decimal('quantity', 10, 2)->nullable();

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->string('description')->nullable();

            $table->text('parameters')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('products.models.quotationrowCandidates.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quotationrow_id')->nullable();
            $table->foreign('quotationrow_id', 'qrow_candidate_quotationrow')->references('id')->on(config('products.models.quotationrow.table'));

            $table->unsignedBigInteger('price_id')->nullable();
            // $table->foreign('price_id')->references('id')->on(config('prices.models.price.table'));

            $table->uuid('sellable_supplier_id', 'sellable_supplier_id')->nullable();
            $table->foreign('sellable_supplier_id', 'qotacandida_sellable_supplier')->references('id')->on(config('products.models.sellableSupplier.table'));

            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('description')->nullable();

            $table->text('parameters')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(! config('products.sellables.enabled', false))
            return ;

        Schema::dropIfExists(config('products.models.quotationrowCandidates.table'));
        Schema::dropIfExists(config('products.models.quotationrow.table'));
    }
};
