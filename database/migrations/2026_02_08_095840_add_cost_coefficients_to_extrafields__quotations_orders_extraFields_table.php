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
        Schema::table('products__extrafields__quotations_orders', function (Blueprint $table) {
            $table->decimal('cost_coefficient', 6, 2)->nullable();
        });

        Schema::table(config('products.models.quotationrow.table'), function (Blueprint $table) {
            $table->decimal('cost_coefficient', 6, 2)->nullable();
        });

        Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
            $table->decimal('cost_coefficient', 6, 2)->nullable();
        });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products__extrafields__quotations_orders', function (Blueprint $table) {
            $table->dropColumn('cost_coefficient');
        });

        Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
            $table->dropColumn('cost_coefficient');
        });

        Schema::table(config('products.models.quotationrow.table'), function (Blueprint $table) {
            $table->dropColumn('cost_coefficient');
        });
    }
};
