<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSizesToProductsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products___sizes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sizeable_type')->nullable();
            $table->string('sizeable_id', 36)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        try
        {
            Schema::table(config('products.models.product.table'), function (Blueprint $table)
            {
                $table->uuid('size_id')->after('client_id')->nullable();
                $table->foreign('size_id')->references('id')->on('products___sizes');
            });            
        }
        catch(\Exception $e)
        {
            echo $e->getMessage() . "<br />";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('products.models.product.table'), function (Blueprint $table) {

            $table->dropIndex(['size_id']);
            $table->dropForeign(['size_id']);
            $table->dropColumn(['size_id']);
        });

        Schema::dropIfExists('products___sizes');
    }
}
