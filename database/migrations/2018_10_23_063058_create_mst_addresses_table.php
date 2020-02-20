<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMstAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_address', function (Blueprint $table) {
            $table->increments('address_id');
            $table->integer('country_id');
            $table->integer('province_id');
            $table->integer('regency_id');
            $table->integer('district_id');
            $table->integer('village_id');
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
        Schema::dropIfExists('mst_addresses');
    }
}
