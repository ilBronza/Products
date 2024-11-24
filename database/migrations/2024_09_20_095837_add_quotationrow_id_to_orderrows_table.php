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
	    Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
		    $table->uuid('quotationrow_id')->nullable();
		    $table->foreign('quotationrow_id')->references('id')->on(config('products.models.quotationrow.table'));
	    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
	        $table->dropForeign(['quotationrow_id']);
	        $table->dropColumn('quotationrow_id');
        });
    }
};
