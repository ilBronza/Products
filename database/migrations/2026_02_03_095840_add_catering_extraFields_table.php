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
        if(is_null(config('products.hasCateringFunctions')))
            dd('please config this key in config.products');

        if((config('products.hasCateringFunctions')) === false)
            return ;

        Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
            $table->string('phase', 64)->nullable();
        });

        Schema::table(config('products.models.quotationrow.table'), function (Blueprint $table) {
            $table->string('phase', 64)->nullable();
        });

        Schema::table('products__extrafields__quotations_orders', function (Blueprint $table) {
            $table->decimal('cost_coefficient', 6, 2)->nullable();
            $table->boolean('served_at_table')->nullable();
            $table->unsignedInteger('people')->nullable();
            $table->text('people_coefficient')->nullable();
            $table->text('phases')->nullable();
        });

	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(is_null(config('products.hasCateringFunctions')))
            dd('please config this key in config.products');

        if((config('products.hasCateringFunctions')) === false)
            return ;

        Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
            $table->dropColumn('phase');
        });

        Schema::table(config('products.models.quotationrow.table'), function (Blueprint $table) {
            $table->dropColumn('phase');
        });

        Schema::table(config('products.models.quotation.table'), function (Blueprint $table) {
            $table->dropColumn('people');
            $table->dropColumn('children');
            $table->dropColumn('people_coefficient');
            $table->dropColumn('phases');
        });

        Schema::table(config('products.models.order.table'), function (Blueprint $table) {
            $table->dropColumn('people');
            $table->dropColumn('children');
            $table->dropColumn('people_coefficient');
            $table->dropColumn('phases');
        });

        Schema::table(config('products.models.product.table'), function (Blueprint $table) {
            $table->dropColumn('served_at_table');
        });

    }
};
