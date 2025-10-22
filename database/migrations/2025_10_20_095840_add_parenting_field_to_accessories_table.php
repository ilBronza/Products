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
        Schema::table(config('products.models.accessory.table'), function (Blueprint $table) {
            $table->unsignedInteger('sorting_index')->after('slug')->nullable();
            $table->uuid('parent_id')->after('slug')->nullable();
        });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	    Schema::table(config('products.models.accessory.table'), function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('sorting_index');
	    });
    }
};
