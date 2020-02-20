<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_customer', function (Blueprint $table) {
            $table->increments('sec_customer_id');
            $table->integer('customer_id');
            $table->integer('member_id');
            $table->string('username');
            $table->string('password');
            $table->string('register_on');
            $table->string('ip_address', 45);
            $table->string('activation_code', 10);
            $table->string('forgot_pass_code', 10);
            $table->string('remember_token');
            $table->time('forgot_pass_date');
            $table->timestamp('last_login')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->integer('active')->default('1');
            $table->timestamp('created_date')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_date_cus')->nullable();
            $table->integer('updated_by_cus')->nullable();
            $table->timestamp('updated_date_emp')->nullable();
            $table->integer('updated_by_emp')->nullable();
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
        Schema::dropIfExists('sec_customers');
    }
}
