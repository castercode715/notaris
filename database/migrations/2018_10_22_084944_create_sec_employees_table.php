<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_employee', function (Blueprint $table) {
            $table->increments('sec_employee_id');
            $table->integer('employee_id')->nullable();
            $table->string('username', 255);
            $table->string('password', 255);
            $table->string('ip_address', 20)->nullable();
            $table->string('remember_token', 255)->nullable();
            $table->string('activation_code', 40)->nullable();
            $table->string('forgot_pass_code', 40)->nullable();
            $table->timestamp('forgot_pass_time');
            $table->timestamp('last_login')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->integer('active')->default('1'); // 1 aktif, 0 inactive, 
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_date')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('sec_employees');
    }
}
