<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrcDetailTransactionAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trc_detail_transaction_asset', function (Blueprint $table) {
            $table->increments('trc_detail_transaction_asset_id');
            $table->integer('trc_transaction_asset_id');
            $table->integer('asset_id');
            $table->integer('price_class_id');
            $table->string('investment_tenor');
            $table->time('investment_duration');
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
        Schema::dropIfExists('trc_detail_transaction_assets');
    }
}
