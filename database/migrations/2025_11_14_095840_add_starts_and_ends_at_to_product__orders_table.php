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
	        $table->timestamp('starts_at')->nullable()->after('date');
	        $table->timestamp('ends_at')->nullable()->after('starts_at');
        });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	    Schema::table(config('products.models.order.table'), function (Blueprint $table) {
		    $table->dropColumn('starts_at');
		    $table->dropColumn('ends_at');
	    });
    }
};
