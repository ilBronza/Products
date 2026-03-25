<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		$table = config('products.models.sellableSupplier.table');

		if(! Schema::hasTable($table))
			return;

		Schema::table($table, function (Blueprint $blueprint) {
			$blueprint->decimal('cost_per_km')->nullable();
			$blueprint->decimal('cost_per_movimentation')->nullable();
			$blueprint->decimal('cost_per_day')->nullable();
		});
	}

	public function down() : void
	{
		$tableName = config('products.models.sellableSupplier.table');

		if(! Schema::hasTable($tableName))
			return;

		Schema::table($tableName, function (Blueprint $blueprint) {
			$blueprint->dropColumn([
				'cost_per_km',
				'cost_per_movimentation',
				'cost_per_day',
			]);
		});
	}
};
