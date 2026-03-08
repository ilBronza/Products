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
            $table->uuid('state_id')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products__extrafields__quotations_orders', function (Blueprint $table) {
            $table->dropColumn('state_id');
        });
    }
};
