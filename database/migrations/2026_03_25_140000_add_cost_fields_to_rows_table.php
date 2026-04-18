<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		// foreach([config('products.models.orderrow.table'), config('products.models.quotationrow.table')] as $table)
		// 	Schema::table($table, function (Blueprint $blueprint) {
		// 		$blueprint->decimal('stored_total_row_revenue')->nullable();
		// 		$blueprint->decimal('stored_single_revenue')->nullable();
		// 		$blueprint->decimal('stored_total_row_cost')->nullable();
		// 		$blueprint->decimal('stored_single_cost')->nullable();
		// 	});
	}

	public function down() : void
	{
		// foreach([config('products.models.orderrow.table'), config('products.models.quotationrow.table')] as $table)
		// 	Schema::table($table, function (Blueprint $blueprint) {
		// 		$blueprint->dropColumn([
		// 			'stored_total_row_revenue',
		// 			'stored_single_revenue',
		// 			'stored_total_row_cost',
		// 			'stored_single_cost',
		// 		]);
		// 	});
	}
};
