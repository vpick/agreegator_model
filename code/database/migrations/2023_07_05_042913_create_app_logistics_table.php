<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_logistics', function (Blueprint $table) {
            $table->id();
			$table->string('logistics_name');
			$table->string('logistics_type');
			$table->string('logistics_auth_key');
			$table->string('logistics_auth_name');
			$table->string('logistics_auth_password');
			$table->string('logistics_auth_secret');
			$table->string('logistics_business_acc');
			$table->string('logistics_currior_id');
			$table->longText('logistics_logo');
			$table->string('logistics_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_logistics');
    }
}
