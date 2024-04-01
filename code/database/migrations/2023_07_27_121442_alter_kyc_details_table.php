<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKycDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kyc_details', function (Blueprint $table) {
            $table->dropColumn(['user_id']);
            // $table->after('id', function ($table) {
            //     $table->foreignId('company_id')->nullable();
            //     $table->foreignId('client_id')->nullable();
            // });
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
