<?php

use IlBronza\Products\Models\Sellables\SellableSupplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		$table = SellableSupplier::gpc()::make()->getTable();

		//aggiungi

		Schema::table(SellableSupplier::gpc()::make()->getTable(), function (Blueprint $table)
		{
			$table->string('sellable_class', 32)->nullable();
		});
	}

	public function down() : void
	{
		$table = SellableSupplier::gpc()::make()->getTable();

		Schema::table($table, function (Blueprint $table)
		{
			$table->dropColumn('sellable_class');
		});
	}
};
