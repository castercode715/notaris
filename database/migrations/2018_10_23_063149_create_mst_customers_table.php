<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMstCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_customer', function (Blueprint $table) {
            $table->increments('customer_id');
            $table->string('customer_number');
            $table->integer('address_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->char('gender', 1);
            $table->date('birth_date');
            $table->text('address');
            $table->string('zip_code', 10);
            $table->string('phone_first', 20);
            $table->string('phone_second', 20);
            $table->string('email');
            $table->string('photo');
            $table->string('npwp_number', 20);
            $table->string('npwp_photo');
            $table->string('ktp_number', 20);
            $table->string('ktp_photo');
            $table->integer('active')->default('1');
            $table->timestamp('created_date')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_date_cus')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->integer('updated_by_cus');
            $table->timestamp('updated_date_emp')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->integer('updated_by_emp');
            $table->timestamp('deleted_date_emp')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->integer('deleted_by_emp');
            $table->integer('is_deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_customers');
    }
}
