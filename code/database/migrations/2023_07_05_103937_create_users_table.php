<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile');
            $table->foreignId('company_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->string('client_map');
            $table->string('warehouse_map');
            $table->enum('multi_location',['1','0'])->default(0);
            $table->enum('multi_client',['1','0'])->default(0);
            $table->enum('user_type',['system','admin','user'])->default('user');
            $table->enum('company_user',['1','0'])->default(0);
            $table->enum('client_user',['1','0'])->default(0);
            $table->enum('status',['1','0'])->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('last_login_date')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
