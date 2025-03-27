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
	    Schema::table(config('products.models.orderProductPhase.table'), function (Blueprint $table) {
		    $table->unsignedInteger('pieces_in')->after('quantity_required')->nullable();
		    $table->unsignedInteger('pieces_out')->after('pieces_in')->nullable();
	    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	    Schema::table(config('products.models.orderProductPhase.table'), function (Blueprint $table) {
		    $table->dropColumn('pieces_in');
		    $table->dropColumn('pieces_out');
	    });
    }
};
