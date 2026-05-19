<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		$relation = \IlBronza\Products\Models\Orders\Orderrow::gpc()::make()->extraFields();

		$table = $relation->getRelated()->getTable();

		//aggiungi

		Schema::table($table, function (Blueprint $table)
		{
			$table->decimal('stored_total_row_revenue')->nullable();
			$table->decimal('stored_single_revenue')->nullable();
			$table->decimal('stored_total_row_cost')->nullable();
			$table->decimal('stored_single_cost')->nullable();

			$table->boolean('approved_total_row_cost')->default(true)->nullable();
			$table->boolean('approved_total_row_revenue')->default(true)->nullable();
		});
	}

	public function down() : void
	{
		$relation = \IlBronza\Products\Models\Orders\Orderrow::gpc()::make()->extraFields();

		$table = $relation->getRelated()->getTable();

		Schema::table($table, function (Blueprint $table)
		{
			$table->dropColumn('stored_total_row_revenue');
			$table->dropColumn('stored_single_revenue');
			$table->dropColumn('stored_total_row_cost');
			$table->dropColumn('stored_single_cost');

			$table->dropColumn('approved_total_row_cost');
			$table->dropColumn('approved_total_row_revenue');
		});
	}
};
