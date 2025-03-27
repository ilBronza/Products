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
		    $table->boolean('frozen')->after('completed_at')->nullable();
	    });

	    Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
		    $table->boolean('frozen')->after('parameters')->nullable();
	    });

	    Schema::table(config('products.models.quotation.table'), function (Blueprint $table) {
		    $table->boolean('frozen')->after('parent_id')->nullable();
	    });

	    Schema::table(config('products.models.quotationrow.table'), function (Blueprint $table) {
		    $table->boolean('frozen')->after('parameters')->nullable();
	    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	    Schema::table(config('products.models.order.table'), function (Blueprint $table) {
		    $table->dropColumn('frozen');
	    });

	    Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
		    $table->dropColumn('frozen');
	    });

	    Schema::table(config('products.models.quotation.table'), function (Blueprint $table) {
		    $table->dropColumn('frozen');
	    });

	    Schema::table(config('products.models.quotationrow.table'), function (Blueprint $table) {
		    $table->dropColumn('frozen');
	    });
    }
};
