<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		$table = config('products.models.product.table');

		Schema::table($table, function (Blueprint $blueprint) {
			$blueprint->decimal('single_revenue')->nullable();
			$blueprint->decimal('single_cost')->nullable();
		});
	}

	public function down() : void
	{
		$tableName = config('products.models.sellableSupplier.table');

		Schema::table($tableName, function (Blueprint $blueprint) {
			$blueprint->dropColumn([
				'single_revenue',
				'single_cost'
			]);
		});
	}
};
