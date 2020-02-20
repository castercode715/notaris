<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sec_module', function (Blueprint $table) {
            $table->increments('sec_module_id');
            $table->string('module');
            $table->string('link');
            $table->string('icon');
            $table->integer('sort');
            $table->integer('parent');
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
        Schema::dropIfExists('sec_modules');
    }
}
