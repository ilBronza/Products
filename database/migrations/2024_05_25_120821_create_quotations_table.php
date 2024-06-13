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
        if(! config('products.sellables.enabled', false))
            return ;

        Schema::create(config('products.models.project.table'), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();

            $table->uuid('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on(config('clients.models.client.table'));

            $table->uuid('destination_id')->nullable();
            $table->foreign('destination_id')->references('id')->on(config('clients.models.destination.table'));

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on(config('category.models.category.table'));

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create(config('products.models.quotation.table'), function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->nullable();

            $table->timestamp('date')->nullable();

            $table->uuid('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on(config('products.models.project.table'));

            $table->uuid('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on(config('clients.models.client.table'));

            $table->uuid('destination_id')->nullable();
            $table->foreign('destination_id')->references('id')->on(config('clients.models.destination.table'));

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(! config('products.sellables.enabled', false))
            return ;

        Schema::dropIfExists(config('products.models.quotation.table'));
        Schema::dropIfExists(config('products.models.project.table'));
    }
};
