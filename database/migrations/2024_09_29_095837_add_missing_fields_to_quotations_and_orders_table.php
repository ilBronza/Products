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
	    Schema::table(config('products.models.order.table'), function (Blueprint $table) {
		    $table->timestamp('date')->after('slug')->nullable();

		    $table->uuid('category_id')->after('destination_id')->nullable();
		    $table->foreign('category_id')->references('id')->on(config('category.models.category.table'));
	    });

	    Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {

//		    $table->uuid('quotationrow_id')->after('id')->nullable();
//		    $table->foreign('quotationrow_id')->references('id')->on(config('products.models.quotationrow.table'));

			$table->string('type')->after('order_id')->nullable();

		    $table->uuid('sellable_id')->after('type')->nullable();
		    $table->foreign('sellable_id')->references('id')->on(config('products.models.sellable.table'));

		    $table->string('description')->after('ends_at')->nullable();
	    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
	    Schema::table(config('products.models.order.table'), function (Blueprint $table) {
		    $table->dropColumn('date');
		    $table->dropForeign(['category_id']);
		    $table->dropColumn('category_id');
	    });

	    Schema::table(config('products.models.orderrow.table'), function (Blueprint $table) {
//		    $table->dropForeign(['quotationrow_id']);
//		    $table->dropColumn('quotationrow_id');
		    $table->dropColumn('type');
		    $table->dropForeign(['sellable_id']);
		    $table->dropColumn('sellable_id');

		    $table->dropColumn('description');
	    });
    }
};
