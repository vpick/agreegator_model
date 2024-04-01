<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErpMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->nullable();
            $table->string('password')->nullable();
            $table->string('auth_key')->nullable();
            $table->string('auth_secret')->nullable();
            $table->string('business_acc')->nullable();
            $table->string('base_of')->default('isCompany');
            $table->foreignId('company_id')->constrained();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->foreignId('erp_id')->constrained();
            $table->enum('status',['1','0'])->default(1);
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('erp_mappings');
    }
}
