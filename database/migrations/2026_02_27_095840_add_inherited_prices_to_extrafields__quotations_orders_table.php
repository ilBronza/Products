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
            $table->decimal('inherited_client_price', 7, 2)->nullable();
            $table->decimal('inherited_company_cost', 7, 2)->nullable();
            $table->decimal('inherited_total', 7, 2)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products__extrafields__quotations_orders', function (Blueprint $table) {
            $table->dropColumn('inherited_client_price');
            $table->dropColumn('inherited_company_cost');
            $table->dropColumn('inherited_total');
        });
    }
};
