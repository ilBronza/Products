<?php

use IlBronza\Products\Models\Orders\OrderExtraFields;
use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	private function getOrderrowExtraFieldsTable() : string
	{
		return Orderrow::gpc()::make()->extraFields()->getRelated()->getTable();
	}

	private function getOrderExtraFieldsTable() : string
	{
		return OrderExtraFields::make()->getTable();
	}

	private function addOrderrowDiscountFields(string $tableName) : void
	{
		Schema::table($tableName, function (Blueprint $table)
		{
			$table->decimal('discount_neat', 10, 2)->nullable();
			$table->decimal('discount_percentage', 6, 2)->nullable();
		});
	}

	private function addOrderDiscountFields(string $tableName) : void
	{
		Schema::table($tableName, function (Blueprint $table)
		{
			$table->string('discount_selection', 24)->nullable();
			$table->decimal('discount_neat', 10, 2)->nullable();
			$table->decimal('discount_percentage', 6, 2)->nullable();
		});
	}

	private function dropOrderrowDiscountFields(string $tableName) : void
	{
		Schema::table($tableName, function (Blueprint $table)
		{
			$table->dropColumn('discount_neat');
			$table->dropColumn('discount_percentage');
		});
	}

	private function dropOrderDiscountFields(string $tableName) : void
	{
		Schema::table($tableName, function (Blueprint $table)
		{
			$table->dropColumn('discount_selection');
			$table->dropColumn('discount_neat');
			$table->dropColumn('discount_percentage');
		});
	}

	public function up() : void
	{
		$this->addOrderrowDiscountFields($this->getOrderrowExtraFieldsTable());
		$this->addOrderDiscountFields($this->getOrderExtraFieldsTable());
	}

	public function down() : void
	{
		$this->dropOrderrowDiscountFields($this->getOrderrowExtraFieldsTable());
		
		$this->dropOrderDiscountFields($this->getOrderExtraFieldsTable());
	}
};
