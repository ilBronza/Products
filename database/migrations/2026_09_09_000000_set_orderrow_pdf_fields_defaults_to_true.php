<?php

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

	public function up() : void
	{
		Schema::table($this->getOrderrowExtraFieldsTable(), function (Blueprint $table)
		{
			$table->boolean('pdf_quotation_show')->nullable()->default(true)->change();
			$table->boolean('pdf_quotation_show_price')->nullable()->default(true)->change();
			$table->boolean('pdf_quotation_show_quantity')->nullable()->default(true)->change();
		});
	}

	public function down() : void
	{
		Schema::table($this->getOrderrowExtraFieldsTable(), function (Blueprint $table)
		{
			$table->boolean('pdf_quotation_show')->nullable()->default(null)->change();
			$table->boolean('pdf_quotation_show_price')->nullable()->default(null)->change();
			$table->boolean('pdf_quotation_show_quantity')->nullable()->default(null)->change();
		});
	}
};
