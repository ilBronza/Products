<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(config('products.models.accessory.table'), function (Blueprint $table) {
            $table->uuid('accessory_type_id')->nullable()->after('parent_id');

            $table->foreign('accessory_type_id')
                ->references('id')
                ->on(config('products.models.accessoryType.table'));
        });
    }

    public function down(): void
    {
        Schema::table(config('products.models.accessory.table'), function (Blueprint $table) {
            $table->dropForeign(['accessory_type_id']);
            $table->dropColumn('accessory_type_id');
        });
    }
};

