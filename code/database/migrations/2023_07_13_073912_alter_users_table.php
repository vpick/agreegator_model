<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('users', function (Blueprint $table) {
            // $table->after('username', function ($table) {
            //     $table->string('user_code')->unique();   
            // }); 
            // $table->after('user_code', function ($table) {
            //     $table->unsignedBigInteger('role_id');           
            //     $table->foreign('role_id')->references('id')->on('roles');
            // });

            // $table->after('role_id', function ($table) {
            //     $table->enum('user_type',['isSystem','isCompany','isClient','isUser'])->nullable();
            // });

            // $table->dropForeign(['client_id']);
            //     $table->foreignId('client_id')
            //     ->nullable()
            //     ->constrained()->change();
            //     $table->unsignedBigInteger('client_id')->nullable()->change();
            //     $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');

            // $table->dropForeign(['warehouse_id']);
            // $table->foreignId('warehouse_id')
            // ->nullable()
            // ->constrained()->change();
            // $table->unsignedBigInteger('warehouse_id')->nullable()->change();
            // $table->foreign('warehouse_id')->references('id')->on('users')->onDelete('cascade');
            // $table->dropColumn('company_user');
            // $table->dropColumn('client_user');
            $table->string('client_map')->nullable()->change();
            $table->string('warehouse_map')->nullable()->change();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
