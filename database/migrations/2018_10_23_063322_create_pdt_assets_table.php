<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdtAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdt_asset', function (Blueprint $table) {
            $table->increments('asset_id');
            $table->integer('pdt_category_asset_id');
            $table->string('asset_name');
            $table->text('desc');
            $table->string('owner_name');
            $table->string('owner_ktp_number');
            $table->string('owner_kk_number');
            $table->integer('price_njop');
            $table->integer('price_market');
            $table->string('credit_tenor');
            $table->string('invesment_tenor');
            $table->integer('active')->default('1');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_date')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('is_deleted')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pdt_assets');
    }
}
