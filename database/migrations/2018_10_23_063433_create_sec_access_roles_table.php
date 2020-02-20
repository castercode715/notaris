<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecAccessRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_access_role', function (Blueprint $table) {
            $table->increments('sec_accessrole_id');
            $table->integer('sec_accesslevel_id');
            $table->integer('sec_employee_id');
            $table->integer('sec_customer_id');
            $table->integer('active')->default('1');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->dateTime('deleted_date')->nullable();
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
        Schema::dropIfExists('sec_access_roles');
    }
}
