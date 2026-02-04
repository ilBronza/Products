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
        Schema::table(config('products.models.quotation.table'), function (Blueprint $table) {
            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();
        });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	    Schema::table(config('products.models.quotation.table'), function (Blueprint $table) {
            $table->dropColumn('starts_at')->nullable();
            $table->dropColumn('ends_at')->nullable();
	    });
    }
};
