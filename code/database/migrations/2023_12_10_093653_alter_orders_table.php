<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->after('height', function ($table) {
                $table->boolean('is_dangrous')->default(0);
            });
            $table->after('order_request', function ($table) {
                $table->boolean('status')->default(0);
            });
            $table->after('order_type', function ($table) {
                $table->string('shipment_mode')->default('');
            });
            $table->after('shipping_state', function ($table) {
                $table->string('shipping_country')->default('');
            });
            $table->after('billing_state', function ($table) {
                $table->string('billing_country')->default('');
            });
            $table->after('order_no', function ($table) {
                $table->string('source')->default('');
                $table->string('channel')->default('');
            });
            $table->after('awb_no', function ($table) {
                $table->string('child_awbno')->default('');
            });
            $table->after('shipping_label', function ($table) {
                $table->string('docket_print')->default('');
            });
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
