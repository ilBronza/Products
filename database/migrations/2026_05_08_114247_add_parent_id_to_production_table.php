<?php

use IlBronza\Products\Models\Quotations\Project;
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
        Schema::table(Project::gpc()::make()->getTable(), function (Blueprint $table) {
            $table->uuid('parent_id')->nullable();

            $table->foreign('parent_id')->references('id')->on(Project::gpc()::make()->getTable());

            $table->unsignedInteger('sorting_index')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(Project::gpc()::make()->getTable(), function (Blueprint $table) {
            $table->dropForeign(['parent_id']);

            $table->dropColumn('sorting_index');
            $table->dropColumn('parent_id');
        });
    }
};
