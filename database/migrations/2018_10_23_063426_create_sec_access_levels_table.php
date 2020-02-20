<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecAccessLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_access_level', function (Blueprint $table) {
            $table->increments('sec_accesslevel_id');
            $table->integer('sec_role_id');
            $table->integer('sec_module_id');
            $table->integer('access_create');
            $table->integer('access_read');
            $table->integer('access_update');
            $table->integer('access_delete');
            $table->integer('access_view');
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
        Schema::dropIfExists('sec_access_levels');
    }
}
