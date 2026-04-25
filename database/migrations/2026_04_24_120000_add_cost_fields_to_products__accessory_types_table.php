<?php

use IlBronza\Products\Models\Orders\Orderrow;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	private function getFields() : array
	{
		return [
			'cost_per_movimentation',
			'cost_per_hour',
			'revenue_per_movimentation',
			'revenue_per_hour'
		];
	}

	public function getTables()
	{
		return [
			config('products.models.accessoryType.table') => '',
			config('products.models.accessory.table') => '',
			Orderrow::gpc()::make()->extraFields()->make()->getTable() => 'stored_'
		];
	}

	public function up() : void
	{
		foreach($this->getTables() as $tableName => $prefix)
			foreach ($this->getFields() as $field) {
				if (! Schema::hasColumn($tableName, $prefix . $field)) {
					Schema::table($tableName, function (Blueprint $table) use ($field, $prefix) {

						echo "added "  . $prefix . $field . "<br />";
						$table->decimal($prefix . $field, 9, 2)->nullable();
					});
				}
			}
	}

	public function down() : void
	{
		foreach($this->getTables() as $tableName => $prefix)
			foreach ($this->getFields() as $field) {
				if (Schema::hasColumn($tableName, $prefix . $field)) {
					Schema::table($tableName, function (Blueprint $table) use ($prefix, $field) {
						echo "removed "  . $prefix . $field . "<br />";

						$table->dropColumn($prefix . $field);
					});
				}
			}
	}
};

