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

        Schema::table(config('products.models.order.table'), function (Blueprint $table) {
            $table->unsignedInteger('people')->nullable();
            $table->unsignedInteger('children')->nullable();
            $table->text('people_coefficient')->nullable();
        });

        Schema::table(config('products.models.quotation.table'), function (Blueprint $table) {
            $table->unsignedInteger('people')->nullable();
            $table->unsignedInteger('children')->nullable();
            $table->text('people_coefficient')->nullable();
        });

        Schema::table(config('products.models.product.table'), function (Blueprint $table) {
            $table->boolean('served_at_table')->nullable();
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

        Schema::table(config('products.models.quotation.table'), function (Blueprint $table) {
            $table->dropColumn('people');
            $table->dropColumn('children');
            $table->dropColumn('people_coefficient');
        });

        Schema::table(config('products.models.order.table'), function (Blueprint $table) {
            $table->dropColumn('people');
            $table->dropColumn('children');
            $table->dropColumn('people_coefficient');
        });

        Schema::table(config('products.models.product.table'), function (Blueprint $table) {
            $table->dropColumn('served_at_table');
        });

    }
};
