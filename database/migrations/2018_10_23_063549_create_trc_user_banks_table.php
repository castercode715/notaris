<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrcUserBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trc_user_bank', function (Blueprint $table) {
            $table->increments('trc_customer_bank_id');
            $table->integer('sec_customer_id');
            $table->integer('bank_id');
            $table->string('account_holder_name');
            $table->integer('account_number');
            $table->string('account_type');
            $table->string('validity_period');
            $table->string('ccv');
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
        Schema::dropIfExists('trc_user_banks');
    }
}
