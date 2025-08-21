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
        Schema::table(config('products.models.phase.table'), function (Blueprint $table) {
            $table->renameColumn('sequence', 'sorting_index');
        });
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	    Schema::table(config('products.models.phase.table'), function (Blueprint $table) {
		    $table->renameColumn('sorting_index', 'sequence');
	    });
    }
};
