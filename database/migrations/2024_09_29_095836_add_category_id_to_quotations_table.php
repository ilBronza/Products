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
	        $table->uuid('category_id')->after('destination_id')->nullable();
	        $table->foreign('category_id')->references('id')->on(config('category.models.category.table'));
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('products.models.quotation.table'), function (Blueprint $table) {
	        $table->dropIndex(['category_id']);
	        $table->dropColumn('category_id');
        });
    }
};
