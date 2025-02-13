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
		if(config('operators.enabled'))
            Schema::table(config('operators.models.workingDay.table'), function (Blueprint $table) {
	            $table->uuid('sellable_supplier_id')->after('operator_id')->nullable();
	            $table->foreign('sellable_supplier_id')->references('id')->on(config('products.models.sellableSupplier.table'));
			});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	    if(config('operators.enabled'))
        Schema::table(config('operators.models.workingDay.table'), function (Blueprint $table) {
	        $table->dropForeign(['sellable_supplier_id']);
	        $table->dropColumn('sellable_supplier_id');
        });
    }
};
