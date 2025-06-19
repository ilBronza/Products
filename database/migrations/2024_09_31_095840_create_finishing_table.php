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
        Schema::create(config('products.models.finishing.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('short_description')->nullable();

			$table->softDeletes();
			$table->timestamps();
        });


        Schema::table(config('products.models.packing.table'), function (Blueprint $table) {
            $table->uuid('finishing_id')->nullable();
            $table->foreign('finishing_id')->references('id')->on(config('products.models.finishing.table'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('products.models.packing.table'), function (Blueprint $table) {
            $table->dropForeign(['finishing_id']);
            $table->dropColumn('finishing_id');
        });

        Schema::dropIfExists(config('products.models.finishing.table'));
    }
};
