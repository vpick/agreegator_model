<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticsMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_mappings', function (Blueprint $table) {
            $table->id();
			$table->string('user_name')->default('');
			$table->string('password')->default('');
			$table->text('auth_key')->default('');
			$table->text('auth_secret')->default('');
			$table->string('business_acc')->default('');
			$table->string('base_of')->default('isCompany');
			$table->string('status')->default('');
			$table->integer('company_id')->default(0);
			$table->integer('client_id')->default(0);
			$table->integer('partner_id')->default(0);
            $table->timestamps();
			
			$table->unique(['company_id', 'client_id','partner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logistics_mappings');
    }
}
