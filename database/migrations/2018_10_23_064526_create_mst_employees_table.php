<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMstEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_employee', function (Blueprint $table) {
            $table->increments('employee_id');
            $table->integer('address_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->char('gender', 1);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->text('address');
            $table->string('zip_code', 10);
            $table->string('phone', 20);
            $table->string('email');
            $table->date('date_work');
            $table->string('photo');
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
        Schema::dropIfExists('mst_employees');
    }
}
