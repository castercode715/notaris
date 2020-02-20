<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrcTransactionAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trc_transaction_asset', function (Blueprint $table) {
            $table->increments('trc_transaction_asset_id');
            $table->integer('sec_customer_id');
            $table->integer('trc_user_bank_id');
            $table->string('invoice');
            $table->date('date');
            $table->date('due_date');
            $table->string('status');
            $table->integer('total_amount');
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
        Schema::dropIfExists('trc_transaction_assets');
    }
}
