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
            $table->dropColumn('people')->nullable();
            $table->dropColumn('children')->nullable();
            $table->dropColumn('people_coefficient')->nullable();
        });

        Schema::table(config('products.models.orders.table'), function (Blueprint $table) {
            $table->dropColumn('people')->nullable();
            $table->dropColumn('children')->nullable();
            $table->dropColumn('people_coefficient')->nullable();
        });
    }
};
