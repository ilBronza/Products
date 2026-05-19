<?php

use IlBronza\Products\Models\Orders\OrderExtraFields;
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
        $table = OrderExtraFields::make()->getTable();

        Schema::table($table, function (Blueprint $table)
        {
            $table->string('mup_selection', 24)->nullable();
            $table->decimal('mup_revenue', 12, 2)->nullable();
            $table->decimal('mup_cost', 12, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = OrderExtraFields::make()->getTable();

        Schema::table($table, function (Blueprint $table)
        {
            $table->dropColumn('mup_selection');
            $table->dropColumn('mup_revenue');
            $table->dropColumn('mup_cost');
        });
    }
};
